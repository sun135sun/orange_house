import os
from google.analytics.data_v1beta import BetaAnalyticsDataClient
from google.analytics.data_v1beta.types import (
    DateRange,
    Dimension,
    Metric,
    RunReportRequest,
    Filter,
    FilterExpression,
    # ✨ 修正點 1: 確保 OrderBy 從 types 模組中正確匯入
    OrderBy, 
)
import pandas as pd

# --- 設定您的參數 (請務必修改此處) ---
# ⚠️ 請替換成您的 GA4 資源 ID
PROPERTY_ID = '510355424' 
CREDENTIALS_PATH = '/Users/lian/my_project/php_project/cms_template/sh_work/py/my/key/my-api-477904-9077242383bb.json'
START_DATE = "2025-12-03" 
END_DATE = "2025-12-03"    
EVENT_NAME = "ad_click"
EVENT_LABEL_DIMENSION = "customEvent:event_label" 

# --- GA4 報告請求邏輯 ---

def get_ga4_detailed_event_report(property_id, start_date, end_date, event_name):
    """
    從 GA4 Data API 獲取指定事件、日期和參數的詳細報表。
    """
    #client = BetaAnalyticsDataClient()
    client = BetaAnalyticsDataClient.from_service_account_file(CREDENTIALS_PATH)

    # 1. 設定過濾條件：只包含特定的事件名稱 (ad_click)
    event_filter = FilterExpression(
        filter=Filter(
            field_name="eventName",
            string_filter=Filter.StringFilter(value=event_name),
        )
    )

    # 2. 設定報告請求
    request = RunReportRequest(
        property=f"properties/{property_id}",
        # 報告維度 (Dimensions): 日期、事件名稱和事件參數 (event_label)
        dimensions=[
            Dimension(name="date"),
            Dimension(name="eventName"),
            Dimension(name=EVENT_LABEL_DIMENSION), 
        ],
        # 報告指標 (Metrics): 事件計數
        metrics=[Metric(name="eventCount")],
        # 日期範圍
        date_ranges=[DateRange(start_date=start_date, end_date=end_date)],
        # 過濾條件
        dimension_filter=event_filter,
        
        # ✨ 修正點 2: 使用正確的 OrderBy 類別結構
        # 這裡設定為按 'date' 升序排序，以確保報表是按時間順序排列
        order_bys=[
            OrderBy(
                dimension=OrderBy.DimensionOrderBy(dimension_name="date"),
                desc=False # False 表示升序 (從小到大)
            ),
             # 也可以選擇加上第二個排序條件，例如按事件計數降序
            OrderBy(
                metric=OrderBy.MetricOrderBy(metric_name="eventCount"),
                desc=True # True 表示降序 (從大到小)
            ),
        ]
    )

    # 3. 發送 API 請求
    response = client.run_report(request)
    return response

# --- 處理回傳的數據並生成報表 (無需修改) ---

def format_response_to_dataframe(response):
    """
    將 GA4 API 回傳的 Response 轉換為 pandas DataFrame。
    """
    dimension_names = [header.name for header in response.dimension_headers]
    metric_names = [header.name for header in response.metric_headers]
    
    data = []
    for row in response.rows:
        row_data = [d.value for d in row.dimension_values] + [m.value for m in row.metric_values]
        data.append(row_data)

    df = pd.DataFrame(data, columns=dimension_names + metric_names)
    
    # 將 GA4 API 回傳的日期 (YYYYMMDD) 格式化
    df['date'] = pd.to_datetime(df['date'], format='%Y%m%d').dt.strftime('%Y-%m-%d')
    # 轉換 eventCount 為整數
    df['eventCount'] = pd.to_numeric(df['eventCount'], errors='coerce').astype('Int64')
    
    return df

# --- 執行主要功能 ---

if __name__ == "__main__":
    print(f"--- 正在查詢 GA4 資源: {PROPERTY_ID} 的 {START_DATE} 至 {END_DATE} 數據 ---")
    
    try:
        # 獲取報告數據
        ga4_response = get_ga4_detailed_event_report(
            PROPERTY_ID, 
            START_DATE, 
            END_DATE, 
            EVENT_NAME
        )
        
        # 轉換為 DataFrame
        report_df = format_response_to_dataframe(ga4_response)

        # 重新命名欄位以更清晰
        report_df.columns = ["日期", "事件名稱", "事件參數 (event_label)", "事件計數"]
        
        # 因為我們已經在 API 請求中排序了，這裡可以省略 pandas 排序，但保留以防萬一
        # report_df = report_df.sort_values(by=["日期", "事件計數"], ascending=[True, False])

        print("\n--- 詳細報表結果 (按日期分類) ---")
        print(report_df)

        # 可選：將報表匯出為 CSV 檔案
        output_filename = f"g5_{START_DATE}_{END_DATE}.csv"
        report_df.to_csv(output_filename, index=False, encoding='utf-8-sig')
        print(f"\n報表已成功匯出至 {output_filename}")

    except Exception as e:
        print(f"發生錯誤: {e}")
        print("請再次檢查您的 PROPERTY_ID、JSON 憑證路徑，以及服務帳號在 GA4 中的權限。")