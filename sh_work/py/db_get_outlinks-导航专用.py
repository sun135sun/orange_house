#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import mysql.connector
from mysql.connector import Error
import csv
import sys
from datetime import datetime, timedelta
import os

# 檢查是否有足夠的命令列參數
if len(sys.argv) < 4:    
    print("📌 用法: python script.py <site> <sid> <now ,yday>")
    sys.exit(1)

# 從命令列獲取參數
site = sys.argv[1]
sid = sys.argv[2]
day = sys.argv[3]

# 取得現在時間
nowday = datetime.now()

# 根據參數決定日期
if day == 'now':
    fday = nowday.strftime('%Y%m%d')
    day_var = 'now'
else:
    yesterday = nowday - timedelta(days=1)
    fday = yesterday.strftime('%Y%m%d')
    day_var = 'yday'

# 檔案名稱與路徑
os.makedirs("./data", exist_ok=True)
csvsrc = f"./data/{site}_db_outlinks_{fday}.csv"

# 資料庫連接配置
db_config = {
    'host': '10.1.1.1',
    'user': 'lian',
    'password': 'lian~wp168',
    'database': 'matomo',
}

# SQL 查詢語句（移除 @變數，直接用 Python 計算日期）
target_date = nowday.date() if day_var == 'now' else (nowday - timedelta(days=1)).date()
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
    lv.idsite = {sid}
    AND lv.location_country IN ('cn', 'hk')
    AND lv.visit_total_time > 1
    AND la_outlink.type = 2
    AND lv.config_device_type != 'desktop'
    AND DATE(TIMESTAMPADD(HOUR, 8, lv.visit_last_action_time)) = '{target_date}'
GROUP BY lv.idvisitor
ORDER BY lv.visit_last_action_time ASC;
"""

# 將查詢結果匯出為 CSV
def export_to_csv(connection, query, output_file):
    try:
        cursor = connection.cursor(dictionary=True)
        cursor.execute(query)
        rows = cursor.fetchall()

        if rows:
            with open(output_file, 'w', newline='', encoding='utf-8') as csv_file:
                writer = csv.DictWriter(csv_file, fieldnames=cursor.column_names)
                writer.writeheader()
                writer.writerows(rows)
            print(f"✅ 數據已成功匯出到 '{output_file}'")
        else:
            print("⚠️ 查無資料，未產生 CSV 檔。")
    except Error as e:
        print(f"❌ 資料庫查詢失敗：{e}")

# 主程式
def main():
    try:
        connection = mysql.connector.connect(**db_config)
        if connection.is_connected():
            print("✅ 成功連接到資料庫")
            export_to_csv(connection, query, csvsrc)
    except Error as e:
        print(f"❌ 資料庫連接失敗：{e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            connection.close()
            print("🔌 資料庫連接已關閉")

if __name__ == "__main__":
    main()
