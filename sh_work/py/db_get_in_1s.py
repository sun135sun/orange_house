#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import mysql.connector
from mysql.connector import Error
import csv
import sys
from datetime import datetime, timedelta

# 檢查是否有足夠的命令列參數
if len(sys.argv) < 4:
    print("❌ 缺少參數！請提供 <site> <sid> <now|yday> 。")
    print("📌 用法: python script.py <site> <sid> <now|yday>")
    sys.exit(1)

# 讀取命令列參數
site = sys.argv[1]
sid = sys.argv[2]
day = sys.argv[3]  # now 或 yday

# 計算日期
nowday = datetime.now()
if day == 'now':
    fday = nowday.strftime('%Y%m%d')
    day_sql = nowday.strftime('%Y-%m-%d')
elif day == 'yday':
    yesterday = nowday - timedelta(days=1)
    fday = yesterday.strftime('%Y%m%d')
    day_sql = yesterday.strftime('%Y-%m-%d')
else:
    print("❌ 無效的日期參數，請使用 'now' 或 'yday'")
    sys.exit(1)

# 匯出檔案名稱
csvsrc = f"./data/{site}_db_in_1s_{fday}.csv"

# 資料庫連線設定
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': 'f3830fb18b0a7bc1',
    'database': 'matomo',
}

# SQL 查詢（注意：這裡只保留 SELECT，並將日期當成參數帶入）
query = f"""
SELECT
    TIMESTAMPADD(HOUR, 8, lv.visit_last_action_time) AS time,
    la_outlink.name AS outlinkUrl  -- 撈取 Outlink URL 的名稱
FROM
    matomo_log_visit lv
JOIN matomo_log_link_visit_action lva ON lv.idvisit = lva.idvisit
JOIN matomo_log_action la_outlink ON lva.idaction_url = la_outlink.idaction
WHERE
    lv.idsite = %s
    AND lv.location_country IN ('cn', 'hk')
    AND lv.visit_total_time > 1
    AND DATE(TIMESTAMPADD(HOUR, 8, lv.visit_last_action_time)) = %s
    AND lva.idaction_url IS NOT NULL -- 確保有記錄 Outlink/Download 的 URL ID
    AND lva.idaction_url != 0
    AND lv.config_device_type != 'desktop'
    AND lv.config_browser_name NOT IN ('Chrome', 'Microsoft Edge')
    -- 建議：若只需 Outlink，可增加條件 lva.idaction_type = 2 
    -- (請以您的 Matomo 資料庫結構確認 Outlink 的 type ID)
GROUP BY lv.idvisitor -- 確保每個訪問者 (Visitor) 只出現一條記錄
ORDER BY lv.visit_last_action_time ASC; 
"""

# 將查詢結果寫入 CSV
def export_to_csv(connection, query, output_file, params):
    try:
        cursor = connection.cursor(dictionary=True)
        cursor.execute(query, params)

        rows = cursor.fetchall()
        if not rows:
            print("⚠️ 查無資料。")
            return

        # 寫入 CSV
        with open(output_file, 'w', newline='', encoding='utf-8') as csv_file:
            writer = csv.DictWriter(csv_file, fieldnames=rows[0].keys())
            writer.writeheader()
            writer.writerows(rows)

        print(f"✅ 數據已成功匯出到 '{output_file}'")
    except Error as e:
        print(f"❌ 資料庫查詢失敗：{e}")

# 主程序
def main():
    try:
        connection = mysql.connector.connect(**db_config)
        if connection.is_connected():
            print(f"✅ 成功連接到資料庫")

            export_to_csv(connection, query, csvsrc, (sid, day_sql))

    except Error as e:
        print(f"❌ 資料庫連接失敗：{e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            connection.close()
            print("🔌 資料庫連接已關閉")

if __name__ == "__main__":
    main()
