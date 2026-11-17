#!/usr/bin/env bash
# 舊站冒煙測試：檢查主要網址的狀態碼與關鍵內容。只發 GET，不會改到資料庫。
# 用法：deploy/smoke.sh [base_url]   預設 http://localhost:8081
set -u
B="${1:-http://localhost:8081}"
fail=0

check() { # check <url> <status> [必須出現的字串]
  local url="$1" want="$2" needle="${3:-}" body code
  body=$(curl -s -w $'\n%{http_code}' "$B$url")
  code="${body##*$'\n'}"; body="${body%$'\n'*}"
  if [ "$code" != "$want" ]; then
    echo "FAIL $url => $code (want $want)"; fail=1; return
  fi
  if [ -n "$needle" ] && ! grep -qF -- "$needle" <<<"$body"; then
    echo "FAIL $url 缺少: $needle"; fail=1; return
  fi
  echo "ok   $url"
}

# 從首頁取一部影片 id
vid=$(curl -s "$B/papapa/" | grep -o '/voddetail/[0-9]*' | head -1 | grep -o '[0-9]*')
if [ -z "$vid" ]; then
  echo "FAIL /papapa/ 取不到影片 id（資料庫連不上？用 docker compose logs web 看錯誤）"
  exit 1
fi
echo "     影片 id = $vid"

# 前台
check /                                   200
check /papapa/                            200 '/voddetail/'
check /oaoaoa                             200 '/voddetail/'
check /hot/                               200
check /devdev/                            200
check /topic/                             200
check /vodtype/200/                       200 '/voddetail/'
check '/vodshow/200--hits---------/'      200 '/voddetail/'
check '/vodsearch/SSIS-------------/'     200
check "/voddetail/$vid/"                  200 "/vodplay/$vid-"
check "/vodplay/$vid-1-1/"                200 'player_aaaa'
check "/index.php/vod/detail/id/$vid.html" 404   # 有定義路由的頁面不能用原始路徑進入（ThinkPHP 規則，正式站相同）
check /rss/index                          200 '<rss'
check /static/js/player.js                200
check /template/web2026/asset/css/stui_default.css 200

# API（fetch / reset 會依 IP 回 403 或資料；這裡只打唯讀的首頁）
check /api.php/link                       200 'link api'

# 後台入口：允許的 IP 會看到登入頁（200 或轉址 302），其他 IP 是 403
code=$(curl -s -o /dev/null -w '%{http_code}' "$B/hwyayl.php")
case "$code" in
  200|302) echo "ok   /hwyayl.php (${code}，目前 IP 可進後台)";;
  403)     echo "ok   /hwyayl.php (403，目前 IP 不在允許清單)";;
  *)       echo "FAIL /hwyayl.php => $code"; fail=1;;
esac

# 不該對外的檔案（Docker 的 Apache 設定 / server-config 範本會擋）
check /install.php                        403
check /template/web2026/html/php/function.php 403
check /application/database.php           403
check /runtime/index.html                 403
check /.user.ini                          403

[ $fail -eq 0 ] && echo "ALL PASSED ($B)" || echo "SOME FAILED ($B)"
exit $fail
