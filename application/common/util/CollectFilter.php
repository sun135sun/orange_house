<?php
namespace app\common\util;

/**
 * 採集資料入庫前的安全檢查（影片 vod、文章 art）
 *
 * 用法（common/model/Collect.php 的 vod_data / art_data）：
 *   1. check($mid, $v)：可疑特徵檢查（特徵和根目錄 db-clean.sh 相同），命中就整筆不入庫
 *   2. clean($mid, $v, $report)：每個欄位依類型清理，$report 回傳「過濾 / 取代了什麼」
 *        禁止欄位   採集源不能設定（模板、跳轉、密碼、點數、鎖定…），直接移除
 *        網址       只接受 http:// https:// // mac://，否則清空
 *        播放網址   「名稱$網址#名稱$網址$$$…」逐集檢查，網址不合格的那一集移除（保留 $$$ 分組數）
 *        HTML       只保留允許的標籤與屬性（簡介 / 劇情 / 文章內文）
 *        其他文字   移除 HTML 標籤與 < > " ` \ 字元
 *      所有字串都會先移除：不合法的 UTF-8、控制字元、零寬 / 方向控制 / BOM 等看不見的字元
 *   3. report($v, $report) / log(...)：採集畫面顯示、寫入 runtime/log/collect_filter.log
 *
 * db-clean.sh 新增特徵時，PATTERN 也要一起加。
 */
class CollectFilter
{
    // 對應 db-clean.sh 的 pattern（MySQL REGEXP → PCRE；[[:space:]] → \s），比對前會轉小寫
    const PATTERN = '~javascript\s*:|(^|[\s"<>/])on[a-z]+\s*=|autofocus|location\s*([.]|=)|atob\s*[(]|eval\s*[(]|<\s*(script|iframe|svg|object|embed)'
        . '|zbfn[.]lnpi6[.]top|msxk[.]53m39[.]top|kgoekj[.]dcaw6s[.]olkllw[.]cn|ly96ymzulmxucgk2lnrvca|ly9tc3hrljuzbtm5lnrvca'
        . '|ahr0chm6ly9tc3hrljuzbtm5lnrvca|cd1sb2nhdglvbi5wyxrobmftzt~';

    const RULES = [
        'vod' => [
            // 命中 PATTERN 就整筆不入庫（db-clean.sh 的播放網址、圖片，加上下載網址）
            'check' => ['vod_play_url', 'vod_down_url', 'vod_pic', 'vod_pic_thumb', 'vod_pic_slide', 'vod_pic_screenshot'],
            // 採集源不能設定：模板（可載入任意檔案）、跳轉 / 版權（導到其他網站）、密碼、點數、鎖定、推薦、顏色、會員組…
            'deny'  => ['group_id', 'vod_tpl', 'vod_tpl_play', 'vod_tpl_down', 'vod_jumpurl', 'vod_copyright',
                        'vod_lock', 'vod_level', 'vod_color', 'vod_points', 'vod_points_play', 'vod_points_down', 'vod_trysee',
                        'vod_pwd', 'vod_pwd_url', 'vod_pwd_play', 'vod_pwd_play_url', 'vod_pwd_down', 'vod_pwd_down_url',
                        'vod_rel_vod', 'vod_rel_art', 'vod_time_hits', 'vod_time_make', 'vod_plot'],
            'url'   => ['vod_pic', 'vod_pic_thumb', 'vod_pic_slide', 'vod_reurl'],
            'list'  => ['vod_play_url', 'vod_down_url', 'vod_pic_screenshot'],
            'html'  => ['vod_content', 'vod_plot_detail'],
        ],
        'art' => [
            'check' => ['art_pic', 'art_pic_thumb', 'art_pic_slide', 'art_pic_screenshot'],
            'deny'  => ['group_id', 'art_tpl', 'art_jumpurl', 'art_lock', 'art_level', 'art_color',
                        'art_points', 'art_points_detail', 'art_pwd', 'art_pwd_url',
                        'art_rel_art', 'art_rel_vod', 'art_time_hits', 'art_time_make'],
            'url'   => ['art_pic', 'art_pic_thumb', 'art_pic_slide'],
            'list'  => ['art_pic_screenshot'],
            'html'  => ['art_content'],
        ],
    ];

    // HTML 允許的標籤；其他標籤拿掉但保留裡面的文字，DROP_TAGS 連內容一起刪除
    const ALLOW_TAGS = ['p', 'br', 'span', 'div', 'section', 'article', 'a', 'font', 'b', 'strong', 'i', 'em', 'u', 's',
        'strike', 'del', 'ins', 'ul', 'ol', 'li', 'dl', 'dt', 'dd', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote',
        'img', 'table', 'caption', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th', 'pre', 'code', 'hr', 'center',
        'small', 'big', 'sub', 'sup'];
    const DROP_TAGS = ['script', 'style', 'iframe', 'frame', 'frameset', 'object', 'embed', 'applet', 'svg', 'math',
        'template', 'noscript', 'textarea', 'select', 'button', 'form', 'input', 'link', 'meta', 'base', 'title', 'head'];
    // 允許的屬性；href / src 另外檢查網址，style 另外檢查內容
    const ALLOW_ATTRS = ['style', 'title', 'align', 'width', 'height', 'colspan', 'rowspan',
        'color', 'face', 'size', 'href', 'target', 'src', 'alt'];
    // style 出現這些就整個 style 拿掉（執行 JS、載入外部資源、蓋版廣告）
    const BAD_STYLE = '~expression|javascript|vbscript|url\s*\(|@import|behavior|binding|position\s*:|z-index~i';

    // html() 處理過程中記錄「可能是攻擊」的移除項目（例如 script×1、onerror 屬性×1）
    private static $htmlRemoved = [];

    /**
     * 可疑特徵檢查（在 clean 之前、用原始資料檢查）
     * @return array|null null = 沒問題；否則 ['field' => 欄位, 'match' => 命中的語法]，整筆不入庫
     */
    public static function check($mid, array $v)
    {
        foreach (self::RULES[$mid]['check'] as $field) {
            if (!isset($v[$field]) || !is_scalar($v[$field]) || $v[$field] === '') {
                continue;
            }
            // 拿掉隱形字元、還原 HTML 實體再比對，避免用 java<零寬>script: 或 &#106;avascript: 繞過
            $text = self::normalize((string)$v[$field]);
            $text = strtolower(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if (preg_match(self::PATTERN, $text, $m)) {
                return ['field' => $field, 'match' => trim($m[0])];
            }
        }
        return null;
    }

    /**
     * 每個欄位依類型清理（check 通過後呼叫）
     * @param array $report 回傳 [欄位 => [說明, …]]，只記錄和攻擊有關的過濾 / 取代（空白、class 屬性等整理不記）
     */
    public static function clean($mid, array $v, &$report = [])
    {
        $report = [];
        $rule = self::RULES[$mid];
        foreach ($v as $field => $value) {
            if (in_array($field, $rule['deny'], true)) {
                if (!self::isEmpty($value)) {
                    $report[$field][] = '採集源不能設定此欄位，已移除（原值：' . self::preview($value) . '）';
                }
                unset($v[$field]);
                continue;
            }
            // 陣列 / 物件：資料表欄位都是純量，沒辦法清理，直接拿掉（例如 JSON 的 dl 已轉成 vod_play_url）
            if (!is_scalar($value) && $value !== null) {
                unset($v[$field]);
                continue;
            }
            if (!is_string($value)) {
                continue;  // 數字、布林、null 由 Collect.php 原本的 intval / floatval 處理
            }

            if (in_array($field, $rule['url'], true)) {
                $new = self::url($value);
                if ($new === '' && trim($value) !== '') {
                    $report[$field][] = '網址不合格，已清空（原值：' . self::preview($value) . '）';
                } elseif ($new !== trim($value)) {
                    $report[$field][] = '移除看不見的字元';
                }
            } elseif (in_array($field, $rule['list'], true)) {
                $dropped = [];
                $new = self::playList($value, $dropped);
                if ($dropped) {
                    $report[$field][] = '移除 ' . count($dropped) . ' 個不合格網址（' . self::preview(implode(' | ', $dropped)) . '）';
                }
            } elseif (in_array($field, $rule['html'], true)) {
                // 文章內文用 $$$ 分頁，每頁分開處理
                self::$htmlRemoved = [];
                $new = implode('$$$', array_map([__CLASS__, 'html'], explode('$$$', $value)));
                if (self::$htmlRemoved) {
                    $parts = [];
                    foreach (self::$htmlRemoved as $what => $n) {
                        $parts[] = $what . '×' . $n;
                    }
                    $report[$field][] = '移除 ' . implode('、', $parts);
                }
            } else {
                $new = self::text($value);
                // 只比對「還原實體、去頭尾空白」後仍不同的情況，避免把 &amp; → & 這種正常轉換也列出來
                $plain = trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                if ($new !== $plain) {
                    $report[$field][] = '移除 HTML 標籤 / 不安全字元（原值：' . self::preview($value) . '）';
                }
            }
            $v[$field] = $new;
        }
        return $v;
    }

    /**
     * 過濾報告轉成一行文字
     * @param bool $html true = 給採集畫面用（HTML 跳脫，原值可能就是攻擊語法）；false = 寫 log 用的原文
     */
    public static function summary(array $report, $html = true)
    {
        $parts = [];
        foreach ($report as $field => $msgs) {
            $parts[] = $field . '：' . implode('；', $msgs);
        }
        $s = implode('　', $parts);
        return $html ? htmlspecialchars($s, ENT_QUOTES, 'UTF-8') : $s;
    }

    /**
     * 寫一行到 runtime/log/collect_filter.log
     * 欄位：時間 / 類型(vod|art) / 採集源 cjflag / 處理(不入庫|已過濾) / 名稱 / 說明
     */
    public static function log($mid, $flag, array $v, $action, $detail)
    {
        $name = isset($v[$mid . '_name']) && is_scalar($v[$mid . '_name']) ? (string)$v[$mid . '_name'] : '';
        $line = date('Y-m-d H:i:s') . "\t" . $mid . "\t" . $flag . "\t" . $action . "\t"
            . self::oneLine($name) . "\t" . self::oneLine($detail) . "\n";
        $dir = RUNTIME_PATH . 'log' . DS;
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents($dir . 'collect_filter.log', $line, FILE_APPEND | LOCK_EX);
    }

    // ---------- 各類型的清理 ----------

    // 一般文字：拿掉標籤與會跳出 HTML 屬性的字元
    public static function text($s)
    {
        $s = html_entity_decode(self::normalize($s), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $s = strip_tags($s);
        return trim(str_replace(['<', '>', '"', '`', '\\'], '', $s));
    }

    // 單一網址：只接受 http:// https:// // mac://（mac:// 是蘋果CMS 的圖片協定佔位）
    public static function url($s)
    {
        $s = trim(self::normalize($s));
        if ($s === '' || !preg_match('~^(https?:|mac:)?//[^\s"\'<>`\\\\]+$~i', $s)) {
            return '';
        }
        return $s;
    }

    /**
     * 播放 / 下載網址、截圖：「名稱$網址#名稱$網址$$$下一組」
     * @param array $dropped 回傳被移除的網址
     */
    public static function playList($s, &$dropped = [])
    {
        $dropped = [];
        $groups = [];
        foreach (explode('$$$', self::normalize($s)) as $group) {
            $items = [];
            foreach (explode('#', $group) as $item) {
                $item = trim($item);
                if ($item === '') {
                    continue;
                }
                $parts = explode('$', $item);
                $url = count($parts) > 1 ? $parts[1] : $parts[0];
                if (!self::playUrl($url)) {
                    $dropped[] = $item;
                    continue;  // 網址不合格的那一集拿掉
                }
                if (count($parts) > 1) {
                    $parts[0] = str_replace(['#', '$'], '', self::text($parts[0]));
                    for ($i = 2; $i < count($parts); $i++) {
                        $parts[$i] = self::text($parts[$i]);
                    }
                }
                $items[] = implode('$', $parts);
            }
            $groups[] = implode('#', $items);   // 整組都不合格也保留空字串，和 vod_play_from 的分組對齊
        }
        return implode('$$$', $groups);
    }

    // 播放網址：http(s):// 或 //，或沒有協定的播放代碼（只能有英數與 ._-/=?&%+）
    private static function playUrl($url)
    {
        $url = trim($url);
        if ($url === '' || preg_match('~[\s"\'<>`\\\\]~', $url)) {
            return false;
        }
        if (preg_match('~^(https?:)?//~i', $url)) {
            return true;
        }
        return strpos($url, ':') === false && preg_match('~^[A-Za-z0-9._\-/=?&%+]+$~', $url) === 1;
    }

    // HTML：只保留允許的標籤與屬性
    public static function html($s)
    {
        $s = self::normalize($s);
        if (strpos($s, '<') === false) {
            return $s;  // 純文字不用解析
        }
        // 落單的 <（例如「a < b」）瀏覽器當文字；libxml 會把後面的字吃掉，先轉成 &lt;
        $s = preg_replace('~<(?![a-zA-Z/!?])~', '&lt;', $s);
        if (!class_exists('DOMDocument')) {
            return strip_tags($s, '<p><br>');  // 沒有 dom 擴充時退回最嚴格的做法
        }
        $doc = new \DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="__cf_root">' . $s . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $doc->getElementById('__cf_root');
        if (!$root) {
            return self::text($s);
        }
        self::cleanNode($root);
        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }
        return $out;
    }

    private static function cleanNode(\DOMNode $node)
    {
        // 先複製一份清單：處理過程會刪除 / 搬移子節點
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }
        foreach ($children as $child) {
            if ($child instanceof \DOMText) {
                continue;
            }
            if (!$child instanceof \DOMElement) {
                $node->removeChild($child);  // 註解、CDATA、處理指令
                continue;
            }
            $tag = strtolower($child->nodeName);
            if (in_array($tag, self::DROP_TAGS, true)) {
                self::removed('<' . $tag . '>');
                $node->removeChild($child);
                continue;
            }
            self::cleanNode($child);
            if (!in_array($tag, self::ALLOW_TAGS, true)) {
                // 不認識的標籤：拿掉標籤、保留裡面的內容（無害，不記錄）
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }
            self::cleanAttrs($child, $tag);
        }
    }

    private static function cleanAttrs(\DOMElement $el, $tag)
    {
        $names = [];
        foreach ($el->attributes as $attr) {
            $names[] = $attr->nodeName;
        }
        foreach ($names as $name) {
            $lname = strtolower($name);
            $value = $el->getAttribute($name);
            $keep = in_array($lname, self::ALLOW_ATTRS, true);
            if ($keep && $lname === 'href') {
                $keep = $tag === 'a' && self::url($value) !== '';
                $keep || self::removed('不合格連結');
            } elseif ($keep && $lname === 'src') {
                $keep = $tag === 'img' && self::url($value) !== '';
                $keep || self::removed('不合格圖片網址');
            } elseif ($keep && $lname === 'style') {
                $keep = !preg_match(self::BAD_STYLE, html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                $keep || self::removed('危險 style');
            } elseif ($keep && $lname === 'target') {
                $keep = $tag === 'a';
            } elseif (!$keep && strpos($lname, 'on') === 0) {
                self::removed($lname . ' 屬性');  // onerror、onclick… 事件屬性（class、id 等無害的不記錄）
            }
            if (!$keep) {
                $el->removeAttribute($name);
            }
        }
        if ($tag === 'a' && $el->hasAttribute('href')) {
            $el->setAttribute('rel', 'nofollow noopener noreferrer');
        }
    }

    private static function removed($what)
    {
        self::$htmlRemoved[$what] = (isset(self::$htmlRemoved[$what]) ? self::$htmlRemoved[$what] : 0) + 1;
    }

    // 不合法的 UTF-8、控制字元（保留 \t \n \r）、零寬 / 方向控制 / BOM 等看不見的字元
    private static function normalize($s)
    {
        if (!mb_check_encoding($s, 'UTF-8')) {
            $s = mb_convert_encoding($s, 'UTF-8', 'UTF-8');
        }
        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $s);
        $r = preg_replace('/[\x{00AD}\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}-\x{2064}\x{2066}-\x{2069}\x{FEFF}]/u', '', $s);
        return $r === null ? $s : $r;
    }

    private static function isEmpty($value)
    {
        return $value === null || $value === '' || $value === 0 || $value === '0' || $value === 0.0 || $value === false;
    }

    // 報告用的原值：單行、最多 80 字（原文，輸出到畫面時由 summary() 統一跳脫）
    private static function preview($value)
    {
        $s = self::oneLine(is_scalar($value) ? (string)$value : json_encode($value));
        if (mb_strlen($s, 'UTF-8') > 80) {
            $s = mb_substr($s, 0, 80, 'UTF-8') . '…';
        }
        return $s;
    }

    private static function oneLine($s)
    {
        return str_replace(["\t", "\r", "\n"], ' ', $s);
    }
}
