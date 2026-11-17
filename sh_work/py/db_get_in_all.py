#!/usr/bin/env python3
# -*- coding: utf-8 -*-
#获取总量 CN HK in
import mysql.connector
from mysql.connector import Error
import csv
import sys
from datetime import datetime, timedelta
import os

# ⬇️ 檢查參數
if len(sys.argv) < 1:
    print("❌ 缺少參數！請提供 `day`。")
    print("📌 用法: python script.py  <now|yday>")
    sys.exit(1)

# ⬇️ 檢查參數
if len(sys.argv) < 4:
    print("❌ 缺少參數！請提供 `site`, `sid`, `day`。")
    print("📌 用法: python script.py <site> <sid> <now|yday>")
    sys.exit(1)

# ⬇️ 讀取參數
site = sys.argv[1]
sid = sys.argv[2]
day_param = sys.argv[3]

# ⬇️ 計算查詢日期
nowday = datetime.now()
if day_param == 'now':
    target_date = nowday
else:
    target_date = nowday - timedelta(days=1)
fday = target_date.strftime('%Y%m%d')
sql_date = target_date.strftime('%Y-%m-%d')

# ⬇️ 輸出檔案路徑
output_dir = "./data"
os.makedirs(output_dir, exist_ok=True)
csv_file_path = os.path.join(output_dir, f"{site}_db_in_{fday}.csv")

# ⬇️ 資料庫連線設定
db_config = {
    'host': '10.1.1.1',
    'user': 'lian',
    'password': 'lian~wp168',
    'database': 'matomo',
}

# ⬇️ SQL 查詢（不再使用多語句）
query = f"""
SELECT         
    TIMESTAMPADD(HOUR, 8, lv.visit_last_action_time) AS time,  
    la.name AS entryPageUrl
FROM 
    matomo_log_visit lv
JOIN matomo_log_link_visit_action lva ON lv.idvisit = lva.idvisit
JOIN matomo_log_action la ON lv.visit_entry_idaction_url = la.idaction
JOIN matomo_log_action la_outlink ON lva.idaction_url = la_outlink.idaction
WHERE 
    lv.idsite = %s
    AND lv.location_country IN ('cn', 'hk')
    AND DATE(TIMESTAMPADD(HOUR, 8, lv.visit_last_action_time)) = %s
GROUP BY lv.idvisitor
ORDER BY lv.visit_last_action_time ASC;
"""

# ⬇️ 匯出資料到 CSV
def export_to_csv(connection, query, output_file, sid, sql_date):
    try:
        cursor = connection.cursor(dictionary=True)
        cursor.execute(query, (sid, sql_date))
        rows = cursor.fetchall()

        if not rows:
            print("⚠️ 查無資料，未產生 CSV。")
            return

        with open(output_file, 'w', newline='', encoding='utf-8') as csv_file:
            writer = csv.DictWriter(csv_file, fieldnames=rows[0].keys())
            writer.writeheader()
            writer.writerows(rows)

        print(f"✅ 數據已成功匯出到 '{output_file}'")

    except Error as e:
        print(f"❌ 資料庫查詢失敗：{e}")

# ⬇️ 主程式
def main():
    try:
        connection = mysql.connector.connect(**db_config)
        if connection.is_connected():
            print(f"✅ 成功連接到資料庫")
            export_to_csv(connection, query, csv_file_path, sid, sql_date)
    except Error as e:
        print(f"❌ 資料庫連接失敗：{e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            connection.close()
            print("🔌 資料庫連接已關閉")

if __name__ == "__main__":
    main()
