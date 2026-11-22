# cms_template（舊站）Docker 測試環境

只用於本機測試。一個指令啟動 PHP 7.4 + Apache、MySQL 5.7（自動匯入資料庫）、Redis。

```
deploy/
├── docker-compose.yml        所有設定都在這裡（port、帳密、允許的 IP），不需要 .env
├── Dockerfile                PHP 7.4 + Apache 映像（php.ini、伪静态規則都寫在裡面）
├── Dockerfile.dockerignore   不放進映像檔的檔案
├── db/db_mysql_data.sql      資料庫備份（不進 git）
└── smoke.sh                  冒煙測試
```

`db/db_mysql_data.sql` 是專案根目錄 `db_mysql_data.sql` 的硬連結，兩個是同一個檔案，不多佔空間
（server_app 也用根目錄那份，所以沒有直接搬走）。如果 `db/` 裡沒有這個檔（例如重新 clone），在 `deploy/` 執行：

```bash
mkdir -p db && ln ../../db_mysql_data.sql db/db_mysql_data.sql
```

## 測試步驟

```bash
cd cms_template/deploy

# 1. 建置並啟動
docker compose up -d --build

# 2. 等 mysql 變 healthy、web 變 running。第一次要匯入 1.3GB，需要幾分鐘到十幾分鐘
docker compose ps
docker compose logs -f mysql      # 看匯入進度

# 3. 冒煙測試，最後一行要是 ALL PASSED
./smoke.sh

# 4. Redis 快取（瀏覽幾頁後），大於 0 表示有在用
docker compose exec redis redis-cli dbsize

# 5. 跳轉池 API
curl http://localhost:8082/api.php/link/fetch
```

瀏覽器檢查：

| 網址 | 預期 |
|---|---|
| http://localhost:8082/papapa/ | 首頁，有影片列表 |
| http://localhost:8082/vodtype/200/ | 分類頁，翻頁正常 |
| 點任一部影片 | 詳情頁 → 播放頁，播放器能播 |
| 搜尋 `SSIS` | 有結果 |
| http://localhost:8082/hwyayl.php | 後台登入頁（帳密和正式站相同） |

## 常用指令

```bash
docker compose logs web            # PHP 錯誤、Apache log
docker compose up -d --build web   # 改了程式 / 模板後重建
docker compose stop                # 停止，資料保留
docker compose down -v             # 連資料庫一起刪除，下次啟動重新匯入
```

## 問題排除

- **後台出現 Access forbidden**：`docker compose logs web | tail` 看每行最前面的來源 IP，
  加到 `docker-compose.yml` 的 `PHP_ADMIN_ALLOWED_IPS`（逗號分隔），再 `docker compose up -d web`。
- **頁面錯誤但看不到原因**：`docker-compose.yml` 的 `PHP_APP_DEBUG` 改成 `"true"`，再 `docker compose up -d web`。
- **後台改的系統設定重建後不見**：系統設定存在容器裡的 `application/extra/*.php`，重建 web 會還原；資料庫的修改不受影響。

## 為了在 Docker 裡跑改過的程式

都是「有設環境變數才生效」，正式主機沒有這些變數，行為和原本相同。

| 檔案 | 改動 |
|---|---|
| `application/database.php` | 資料庫連線可用 `PHP_DATABASE_*` 覆蓋 |
| `application/common/behavior/Init.php` | Redis 主機可用 `PHP_CACHE_*` 覆蓋（原本寫死 127.0.0.1） |
| `hwyayl.php`、`application/api/controller/Link.php` | 可用 `PHP_ADMIN_ALLOWED_IPS` 額外允許 IP |
