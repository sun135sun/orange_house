#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import mysql.connector
from mysql.connector import Error
import csv
import sys
import os
from datetime import datetime, timedelta

# 檢查命令列參數
if len(sys.argv) < 4:
    print("❌ 缺少參數！請提供 <site> <sid> <now|yday>")
    print("📌 用法: python script.py <site> <sid> <now|yday>")
    sys.exit(1)

# 接收參數
site = sys.argv[1]
sid = sys.argv[2]
day_param = sys.argv[3]

# 計算日期
nowday = datetime.now()
if day_param == 'now':
    target_date = nowday
elif day_param == 'yday':
    target_date = nowday - timedelta(days=1)
else:
    print("❌ 第三個參數必須是 'now' 或 'yday'")
    sys.exit(1)

fday = target_date.strftime('%Y%m%d')
sql_date = target_date.strftime('%Y-%m-%d')

# 建立輸出資料夾與檔名
output_dir = "./data"
os.makedirs(output_dir, exist_ok=True)
csvsrc = os.path.join(output_dir, f"{site}_db_out_{fday}.csv")

# 資料庫設定
db_config = {
    'host': '10.1.1.1',
    'user': 'lian',
    'password': 'lian~wp168',
    'database': 'matomo',
}

# SQL 查詢（單一查詢語句）
query = """
SELECT     
    TIMESTAMPADD(HOUR, 8, lv.visit_last_action_time) AS time,      
    la.name AS entryPageUrl
FROM 
    matomo_log_visit lv
JOIN 
    matomo_log_link_visit_action lva 
    ON lv.idvisit = lva.idvisit
JOIN 
    matomo_log_action la 
    ON lva.idaction_url = la.idaction
WHERE 
    lv.idsite = %s
    AND la.type = 2
    AND DATE(TIMESTAMPADD(HOUR, 8, lv.visit_last_action_time)) = %s
GROUP BY 
    lv.idvisitor, lva.idaction_url, lv.visit_last_action_time, la.name
ORDER BY 
    lv.visit_last_action_time ASC;
"""

# 匯出成 CSV
def export_to_csv(connection, query, output_file, sid, date_str):
    try:
        cursor = connection.cursor(dictionary=True)
        cursor.execute(query, (sid, date_str))
        rows = cursor.fetchall()

        if not rows:
            print("⚠️ 查無資料，未匯出 CSV。")
            return

        with open(output_file, 'w', newline='', encoding='utf-8') as csv_file:
            writer = csv.DictWriter(csv_file, fieldnames=rows[0].keys())
            writer.writeheader()
            writer.writerows(rows)

        print(f"✅ 數據已成功匯出到 '{output_file}'")
    except Error as e:
        print(f"❌ 查詢失敗：{e}")

# 主程式
def main():
    try:
        connection = mysql.connector.connect(**db_config)
        if connection.is_connected():
            print("✅ 成功連接到資料庫")
            export_to_csv(connection, query, csvsrc, sid, sql_date)
    except Error as e:
        print(f"❌ 資料庫連接失敗：{e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            connection.close()
            print("🔌 資料庫連接已關閉")

if __name__ == "__main__":
    main()
