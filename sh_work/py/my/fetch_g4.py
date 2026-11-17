import pandas as pd
from google.analytics.data_v1beta import BetaAnalyticsDataClient
from google.analytics.data_v1beta.types import (
    DateRange,
    Dimension,
    Metric,
    Filter,
    FilterExpression,
    RunReportRequest,
    OrderBy,
)
PROPERTY_ID = '510355424'
CREDENTIALS_PATH = '/Users/lian/my_project/php_project/cms_template/sh_work/py/my/key/my-api-477904-9077242383bb.json'
START_DATE = "2025-12-03" 
END_DATE = "2025-12-03"  

def run_scroll_event_by_source_report(start_date: str = "30daysAgo", end_date: str = "today"):
    """
    運行 GA4 報告，篩選 'scroll' 事件，並按日期和網站來源 (sessionSource) 統計次數。
    """
    
    try:
        client = BetaAnalyticsDataClient.from_service_account_file(CREDENTIALS_PATH)

        request = RunReportRequest(
            property=f"properties/{PROPERTY_ID}",
            # --- 维度：日期 和 网站来源/媒介 ---
            dimensions=[
                Dimension(name="date"),                 # 日期
                Dimension(name="sessionSource"),  # 网站来源/媒介
            ],
            # --- 指标：事件計數 ---
            metrics=[
                Metric(name="eventCount"),  # 統計 scroll 事件總次數
                Metric(name="totalUsers"),  # 統計觸發 scroll 事件的總人數
            ],
            # --- 筛选器：只获取事件名称为 'scroll' 的数据 ---
            dimension_filter=FilterExpression(
                filter=Filter(
                    field_name="eventName",
                    string_filter=Filter.StringFilter(value="scroll"),
                )
            ),
            # 日期範圍
            date_ranges=[DateRange(start_date=start_date, end_date=end_date)],
            
            # 排序：按日期升序和事件計數降序排列
            order_bys=[
                OrderBy(dimension={'dimension_name': 'date'}, desc=False),
                OrderBy(metric={'metric_name': 'eventCount'}, desc=True)
            ],
        )

        print(f"正在從 GA4 API 獲取 '{start_date}' 到 '{end_date}' 的 'scroll' 事件數據...")
        response = client.run_report(request)
        print("數據獲取成功。")

        # --- 將 API 響應轉換為 pandas DataFrame ---
        data = []
        for row in response.rows:
            date_value = row.dimension_values[0].value
            source_medium = row.dimension_values[1].value
            event_count = row.metric_values[0].value
            total_users = row.metric_values[1].value
            
            # 格式化日期：YYYYMMDD 轉為 YYYY-MM-DD
            formatted_date = f"{date_value[:4]}-{date_value[4:6]}-{date_value[6:]}"
            
            data.append([formatted_date, source_medium, event_count, total_users])

        columns = ['日期', '網站來源', 'Scroll 事件計數', 'Scroll 觸發人數']
        df = pd.DataFrame(data, columns=columns)
        
        # 轉換數值類型
        for col in ['Scroll 事件計數', 'Scroll 觸發人數']:
             df[col] = pd.to_numeric(df[col], errors='coerce')
             
        return df

    except Exception as e:
        print(f"發生錯誤：{e}")
        print("\n請檢查您的配置信息和 GA4 權限是否正確。")
        return None



def export_to_csv(df, filename="g4_"+START_DATE+"_"+END_DATE+".csv"):
    """將 DataFrame 匯出為 CSV 文件""" 
    try:
        # 使用 'utf-8-sig' 編碼確保中文在 Excel 中能正確顯示
        df.to_csv(filename, index=False, encoding='utf-8-sig') 
        print(f"\n--- 報告已成功匯出至 CSV 文件 ---")
        print(f"文件名: {filename}")
    except Exception as e:
        print(f"\n--- 匯出 CSV 失敗 ---")
        print(f"錯誤: {e}")
# --- 運行主程序並顯示結果 ---
if __name__ == "__main__":
     
    
    scroll_df = run_scroll_event_by_source_report(
        start_date=START_DATE, 
        end_date=END_DATE
    )

    if scroll_df is not None:
        
        # 總結分析：按網站來源匯總總數
        source_summary = scroll_df.groupby('網站來源').agg(
            總Scroll次數=('Scroll 事件計數', 'sum'),
            總Scroll人數=('Scroll 觸發人數', 'sum')
        ).reset_index().sort_values(by='總Scroll次數', ascending=False)
        
        print("\n--- 網站來源 Scroll 事件總結報告 ---")
        print(source_summary.to_string(index=False))

        print("\n--- 原始數據   ---")
        print(scroll_df.to_string(index=False))

    export_to_csv(scroll_df)