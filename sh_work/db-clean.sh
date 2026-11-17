#!/usr/bin/env bash
# MacCMS 可疑資料清理：預設只掃描；--apply 才允許逐類確認刪除整筆影片。
# Bash 4+ / MySQL 5.7+ 或支援 SHA2 的 MariaDB。請先暫停採集及資料寫入。
# Exit: 0=無命中；1=有命中（包括已處理）；2=錯誤／不完整。
set -u
set -o pipefail
umask 077
exec 3<&0

usage() {
    cat <<'HELP'
用法：bash db-clean.sh [--scan|--apply]
  --scan   只掃描，不修改資料庫（預設）。
  --apply  保存命中列的還原 SQL，逐資料庫／分類輸入 DELETE 才刪除整筆影片。

環境變數：
  DB_NAME       只處理指定資料庫；未設定則掃描可見的同名影片表。
  TABLE_NAME    影片表名稱，預設 mac_vod，可指定其他前綴。
  DB_USER       可選，覆寫設定檔中的 MySQL 帳號。
  DB_HOST       可選，指定 TCP 主機。
  DB_PORT       可選，TCP 埠號（搭配 DB_HOST）。
  DB_SOCKET     可選，Unix socket（不能與 DB_HOST 同用）。
  MYSQL_BIN     mysql client 路徑，預設 mysql。
  MYSQL_CNF     MySQL 用戶端設定檔，預設 /etc/my.cnf。
  BACKUP_DIR    --apply 的備份根目錄，預設目前目錄下 maccms-backups。

帳密由 mysql 讀取設定檔的 [client]／[mysql] 區段，不提示輸入密碼。
還原 SQL 只備份命中列，不含建表結構。
HELP
}
mode=scan
case ${1:---scan} in
    --scan) ;;
    --apply) mode=apply ;;
    --help|-h) usage; exit 0 ;;
    *) usage >&2; exit 2 ;;
esac
(( $# <= 1 )) || { usage >&2; exit 2; }
die() { printf '錯誤：%s\n' "$*" >&2; exit 2; }
mysql_bin=${MYSQL_BIN:-mysql}
mysql_cnf=${MYSQL_CNF:-/etc/my.cnf}
table=${TABLE_NAME:-mac_vod}
[[ $table =~ ^[a-zA-Z_][a-zA-Z0-9_]*$ ]] || die 'TABLE_NAME 僅支援英文字母、數字、底線。'
[[ -z ${DB_PORT:-} || ${DB_PORT} =~ ^[0-9]+$ ]] || die 'DB_PORT 必須是數字。'
[[ -z ${DB_PORT:-} || -n ${DB_HOST:-} ]] || die '設定 DB_PORT 時也須設定 DB_HOST。'
[[ -z ${DB_SOCKET:-} || -z ${DB_HOST:-} ]] || die 'DB_SOCKET 和 DB_HOST 不能同時設定。'
command -v "$mysql_bin" >/dev/null 2>&1 || die "找不到 mysql client：$mysql_bin"
[[ -f $mysql_cnf && -r $mysql_cnf ]] || die "MySQL 設定檔不存在或無法讀取：$mysql_cnf"
for utility in mktemp cut cat rm rmdir mkdir; do
    command -v "$utility" >/dev/null 2>&1 || die "找不到工具：$utility"
done
run_dir=$(mktemp -d "${TMPDIR:-/tmp}/maccms-clean.XXXXXXXX") || exit 2
cleanup() {
    rm -f -- "$run_dir/snapshot.tsv" "$run_dir/keys.sql" "$run_dir/delete.sql"
    rmdir -- "$run_dir" 2>/dev/null || true
}
trap cleanup EXIT
trap 'exit 130' INT
trap 'exit 143' TERM HUP

printf '模式：%s；MySQL 設定檔：%s；影片表：%s\n' "$mode" "$mysql_cnf" "$table"
# Let mysql parse credentials; never read, print, or copy the password in this script.
# --defaults-file must be the first client option.
mysql_cmd=("$mysql_bin" "--defaults-file=$mysql_cnf"
    --batch --skip-column-names --default-character-set=utf8mb4 --connect-timeout=10 --skip-force --local-infile=0
    "--init-command=SET SESSION time_zone='+00:00'")
[[ -z ${DB_USER:-} ]] || mysql_cmd+=("--user=$DB_USER")
[[ -z ${DB_HOST:-} ]] || mysql_cmd+=("--host=$DB_HOST" --protocol=TCP)
[[ -z ${DB_PORT:-} ]] || mysql_cmd+=("--port=$DB_PORT")
[[ -z ${DB_SOCKET:-} ]] || mysql_cmd+=("--socket=$DB_SOCKET" --protocol=SOCKET)
query() { "${mysql_cmd[@]}" "$@"; }
query --execute='SELECT 1;' >/dev/null || die '連線驗證失敗，請檢查設定檔的 [client]／[mysql] 帳密與連線參數。'
databases=$(query --execute="SELECT TABLE_SCHEMA FROM information_schema.TABLES
    WHERE TABLE_NAME='$table' AND TABLE_TYPE='BASE TABLE'
    AND TABLE_SCHEMA NOT IN ('information_schema','performance_schema','mysql','sys')
    ORDER BY TABLE_SCHEMA;") || exit 2
[[ -n $databases ]] || die "目前帳號可見範圍沒有 $table 表。"

# Both clear-text domains and the two nested Base64 destinations seen in the incident.
# Event handlers alone also catch the outer, fully encoded payload.
pattern='javascript[[:space:]]*:|(^|[[:space:]"<>/])on[a-z]+[[:space:]]*=|autofocus|location[[:space:]]*([.]|=)|atob[[:space:]]*[(]|eval[[:space:]]*[(]|<[[:space:]]*(script|iframe|svg|object|embed)|zbfn[.]lnpi6[.]top|msxk[.]53m39[.]top|kgoekj[.]dcaw6s[.]olkllw[.]cn|ly96ymzulmxucgk2lnrvca|ly9tc3hrljuzbtm5lnrvca'
pattern+='|ahr0chm6ly9tc3hrljuzbtm5lnrvca|cd1sb2nhdglvbi5wyxrobmftzt'

errors=0 scanned=0 found=0 deleted_play=0 deleted_picture=0 selected=0
backup_run=''
printf '命中代表可疑特徵，需核對內容；清理模式會刪除整筆影片及其播放資料。\n'
printf '每個 DB 分播放網址／圖片處理。Enter 或任何非 DELETE 回答均保留資料。\n'
while IFS= read -r -u 4 db; do
    [[ -n $db ]] || continue
    [[ -z ${DB_NAME:-} || $db == "$DB_NAME" ]] || continue
    selected=$((selected + 1))
    # Strict identifiers prevent database names from altering generated SQL or backup paths.
    if [[ ! $db =~ ^[a-zA-Z0-9_][a-zA-Z0-9_-]*$ ]]; then
        printf '不支援的資料庫名稱，請人工處理：%s\n' "$db" >&2
        errors=$((errors + 1)); continue
    fi
    printf '\n[資料庫] %s\n' "$db"
    metadata=$(query "--database=$db" --execute="SELECT COLUMN_NAME,DATA_TYPE,EXTRA FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$table' ORDER BY ORDINAL_POSITION;") || { errors=$((errors + 1)); continue; }
    columns=$'\n'
    hash_v="SHA2(CONCAT_WS(':',"
    insert_columns=''
    values_expr=''
    bad_columns=0
    while IFS=$'\t' read -r column data_type extra; do
        [[ $column =~ ^[a-zA-Z_][a-zA-Z0-9_]*$ ]] || { bad_columns=1; break; }
        columns+="$column"$'\n'
        hash_v+="COALESCE(HEX(CAST(v.\`$column\` AS BINARY)),'NULL'),"
        [[ $extra != *'VIRTUAL GENERATED'* && $extra != *'STORED GENERATED'* ]] || continue
        insert_columns+="\`$column\`,"
        case $data_type in
            binary|varbinary|tinyblob|blob|mediumblob|longblob)
                value_expr="COALESCE(CONCAT('UNHEX(',CHAR(39),HEX(v.\`$column\`),CHAR(39),')'),'NULL')" ;;
            tinyint|smallint|mediumint|int|bigint|decimal|float|double|char|varchar|tinytext|text|mediumtext|longtext|enum|set|date|datetime|timestamp|time|year|json)
                value_expr="COALESCE(CONCAT('CONVERT(UNHEX(',CHAR(39),HEX(CONVERT(v.\`$column\` USING utf8mb4)),CHAR(39),') USING utf8mb4)'),'NULL')" ;;
            *) bad_columns=1; break ;;
        esac
        values_expr+="$value_expr,',',"
    done <<< "$metadata"
    has_column() { [[ $columns == *$'\n'"$1"$'\n'* ]]; }
    if (( bad_columns )) || ! has_column vod_id || ! has_column vod_name || [[ -z $insert_columns ]]; then
        printf '欄位結構／型別不支援，或缺少 vod_id/vod_name；略過此表，不修改。\n' >&2
        errors=$((errors + 1)); continue
    fi
    hash_v="${hash_v%,}),256)"
    insert_columns=${insert_columns%,}
    values_expr=${values_expr%,\',\',}
    # Each scan includes an ASCII-only INSERT of that exact row in the same SELECT.
    # All values are hex-encoded; data can never become SQL syntax in the restore file.
    restore_expr="CONCAT('INSERT INTO \`$db\`.\`$table\` ($insert_columns) VALUES (',$values_expr,');')"

    for category in PLAY PICTURE; do
        if [[ $category == PLAY ]]; then
            category_name='播放網址'; fields=(vod_play_url)
        else
            category_name='圖片（封面／縮圖／輪播圖／截圖）'; fields=(vod_pic vod_pic_thumb vod_pic_slide vod_pic_screenshot)
        fi
        printf '\n[%s]\n' "$category_name"
        predicate=''; display_fields=''; display_header='vod_id\tvod_name'
        for field in "${fields[@]}"; do
            has_column "$field" || continue
            predicate+="LOWER(COALESCE(v.\`$field\`,'')) REGEXP '$pattern' OR "
            display_fields+=",v.\`$field\`"
            display_header+="\\t$field"
        done
        [[ -n $predicate ]] || { printf '沒有可掃描欄位。\n'; continue; }
        predicate=${predicate%' OR '}
        if ! query "--database=$db" --execute="/* SCAN_$category */ SELECT v.vod_id,$hash_v,$restore_expr,v.vod_name$display_fields
            FROM \`$table\` AS v WHERE ($predicate) ORDER BY v.vod_id;" > "$run_dir/snapshot.tsv"; then
            printf '掃描失敗；不執行此分類。\n' >&2
            errors=$((errors + 1)); continue
        fi
        scanned=$((scanned + 1)); count=0; malformed=0
        : > "$run_dir/keys.sql" || exit 2
        while IFS=$'\t' read -r id fingerprint restore_sql rest; do
            [[ -n $id ]] || continue
            if [[ ! $id =~ ^[0-9]+$ || ! $fingerprint =~ ^[[:xdigit:]]{64}$ || $restore_sql != 'INSERT INTO '* || $restore_sql != *');' ]]; then
                malformed=1; break
            fi
            printf "INSERT INTO scan_keys VALUES (%s,'%s');\n" "$id" "$fingerprint" >> "$run_dir/keys.sql" || exit 2
            count=$((count + 1))
        done < "$run_dir/snapshot.tsv"
        if (( malformed )); then
            printf '掃描格式異常或 SHA2 不可用，禁止刪除。\n' >&2
            errors=$((errors + 1)); continue
        fi
        (( count > 0 )) || { printf '未命中本次特徵。\n'; continue; }
        found=$((found + count))
        printf '命中 %s 筆（本分類去重）：\n' "$count"
        printf '%b\n' "$display_header"
        cut -f 1,4- "$run_dir/snapshot.tsv" || exit 2
        [[ $mode == apply ]] || { printf '只掃描：未修改資料庫。\n'; continue; }

        blockers=$(query "--database=$db" --execute="SELECT
          (SELECT COUNT(*) FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA=DATABASE()
             AND EVENT_OBJECT_TABLE='$table' AND EVENT_MANIPULATION='DELETE') +
          (SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA=DATABASE()
             AND REFERENCED_TABLE_NAME='$table') +
          IF((SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE()
             AND TABLE_NAME='$table' AND INDEX_NAME='PRIMARY')=1 AND
             (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE()
             AND TABLE_NAME='$table' AND INDEX_NAME='PRIMARY' AND COLUMN_NAME='vod_id')=1,0,1);") || { errors=$((errors + 1)); continue; }
        if [[ $blockers != 0 ]]; then
            printf '有刪除觸發器、被外鍵引用或主鍵不相容；需人工處理，不自動刪除。\n' >&2
            errors=$((errors + 1)); continue
        fi
        if [[ -z $backup_run ]]; then
            backup_root=${BACKUP_DIR:-"$PWD/maccms-backups"}
            mkdir -p -- "$backup_root" || die '無法建立備份目錄。'
            backup_run=$(mktemp -d "$backup_root/run.XXXXXXXX") || die '無法建立獨立備份目錄。'
        fi
        backup_file="$backup_run/$db-$category-restore.sql"
        {
            printf -- '-- Pre-delete snapshot: %s / %s / %s rows\n' "$db" "$category" "$count" || exit 2
            printf -- '-- Includes skipped/changed rows. Review before restore; INSERT does not overwrite existing IDs.\n' || exit 2
            printf 'SET @maccms_old_sql_mode=@@SESSION.sql_mode;\nSET SESSION sql_mode=\x27NO_AUTO_VALUE_ON_ZERO\x27;\n' || exit 2
            printf 'SET @maccms_old_time_zone=@@SESSION.time_zone;\nSET SESSION time_zone=\x27+00:00\x27;\n' || exit 2
            cut -f 3 "$run_dir/snapshot.tsv" || exit 2
            printf 'SET SESSION sql_mode=@maccms_old_sql_mode;\nSET SESSION time_zone=@maccms_old_time_zone;\n'
        } > "$backup_file" || die '備份失敗，停止；未執行本次刪除。'
        [[ -s $backup_file ]] || die '備份檔案為空。'
        printf '已保存還原 SQL：%s\n' "$backup_file"
        printf '刪除 DB [%s] 的「%s」上述 %s 筆完整影片？輸入 DELETE 確認，Enter 略過：' "$db" "$category_name" "$count"
        answer=''
        if ! IFS= read -r answer <&3 || [[ $answer != DELETE ]]; then
            printf '\n已略過，未修改。\n'; continue
        fi
        {
            printf 'CREATE TEMPORARY TABLE scan_keys (vod_id BIGINT UNSIGNED PRIMARY KEY, row_hash CHAR(64) NOT NULL);\n' || exit 2
            cat "$run_dir/keys.sql" || exit 2
            printf 'DELETE v FROM `%s` AS v INNER JOIN scan_keys AS k ON k.vod_id=v.vod_id WHERE %s=k.row_hash;\n' "$table" "$hash_v" || exit 2
            printf 'SELECT ROW_COUNT();\n'
        } > "$run_dir/delete.sql" || exit 2
        if ! deleted=$(query "--database=$db" < "$run_dir/delete.sql"); then
            printf '刪除回報錯誤，已停止。可能已有部分變更，請核對現況與備份：%s\n' "$backup_file" >&2
            exit 2
        fi
        [[ $deleted =~ ^[0-9]+$ ]] && (( deleted <= count )) || die '刪除回傳異常，請核對實際狀態。'
        if [[ $category == PLAY ]]; then deleted_play=$((deleted_play + deleted));
        else deleted_picture=$((deleted_picture + deleted)); fi
        printf '[%s] 已刪除 %s 筆；掃描後異動／消失而未刪除 %s 筆。\n' "$category_name" "$deleted" "$((count - deleted))"
    done
done 4<<< "$databases"

(( selected > 0 )) || die '找不到指定資料庫的影片表，請檢查 DB_NAME / TABLE_NAME 與帳號權限。'
printf '\n完成：掃描分類 %s、分類命中次數 %s、播放類刪除 %s、圖片類刪除 %s、異常 %s。\n' \
    "$scanned" "$found" "$deleted_play" "$deleted_picture" "$errors"
printf '同一影片可能在兩類重複命中。零命中不代表整站乾淨。\n'
printf '清理後還需修正採集／模板入口，更新 CMS／靜態頁／CDN 快取。\n'
[[ -z $backup_run ]] || printf '備份保留於：%s（不會隨腳本結束刪除）。\n' "$backup_run"
if (( errors > 0 || scanned == 0 )); then exit 2; fi
if (( found > 0 )); then exit 1; fi
exit 0
