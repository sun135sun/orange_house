import pandas as pd
from google.analytics.data_v1beta import BetaAnalyticsDataClient
from google.analytics.data_v1beta.types import (
    DateRange,
    Dimension,
    Metric,
    RunReportRequest,
    OrderBy,
)
#512217299
PROPERTY_ID = '510355424'
CREDENTIALS_PATH = '/Users/lian/my_project/php_project/cms_template/sh_work/py/my/key/my-api-477904-9077242383bb.json'
START_DATE = "2025-12-04" 
END_DATE = "2025-12-04"  
def run_ga4_engaged_sessions_by_source():
    """从 GA4 API 运行报告，按 '工作階段來源 / 媒介' 分类并获取参与工作階段数。"""
    try:
        client = BetaAnalyticsDataClient.from_service_account_file(CREDENTIALS_PATH)

        request = RunReportRequest(
            property=f"properties/{PROPERTY_ID}",
            # 维度：按来源/媒介分类
            dimensions=[
                Dimension(name="sessionSource"), 
                Dimension(name="date"),                
            ],
            # --- 关键修改：使用 engagedSessions (参与工作階段) ---
            metrics=[
                Metric(name="sessions"),          # 总工作階段数
                Metric(name="engagedSessions"),   # 参与工作階段数 (优质工作階段)
                Metric(name="engagementRate"),    # 参与率
            ],
            # 日期范围
            date_ranges=[DateRange(start_date=START_DATE, end_date=END_DATE)],
            # 排序：按参与工作階段数降序排列
            order_bys=[OrderBy(metric={'metric_name': 'engagedSessions'}, desc=True)],
        )

        print("正在从 GA4 API 获取数据 (按来源/媒介分类，使用参与工作階段)...")
        response = client.run_report(request)
        print("数据获取成功。")

        # --- 将 API 响应转换为 pandas DataFrame ---
        data = []
        for row in response.rows:
            source_medium_value = row.dimension_values[0].value
            date_value = row.dimension_values[1].value
            metric_values = [v.value for v in row.metric_values]
            
            # 格式化日期
            formatted_date = f"{date_value[:4]}-{date_value[4:6]}-{date_value[6:]}"
            
            data.append([source_medium_value, formatted_date] + metric_values)

        column_names = [d.name for d in response.dimension_headers] + \
                       [m.name for m in response.metric_headers]

        df = pd.DataFrame(data, columns=column_names)
        
        # 将指标列转换为数值类型
        for col in column_names[2:]:
             df[col] = pd.to_numeric(df[col], errors='coerce')
             
        # 格式化参与率
        df['engagementRate'] = (df['engagementRate'] * 100).round(2).astype(str) + '%'
        
        return df

    except Exception as e:
        print(f"发生错误：{e}")
        print("\n请检查您的配置信息和 GA4 权限是否正确。")
        return None


def export_to_csv(df, filename="g3_"+START_DATE+"_"+END_DATE+".csv"):
    """將 DataFrame 匯出為 CSV 文件""" 
    try:
        # 使用 'utf-8-sig' 編碼確保中文在 Excel 中能正確顯示
        df.to_csv(filename, index=False, encoding='utf-8-sig') 
        print(f"\n--- 報告已成功匯出至 CSV 文件 ---")
        print(f"文件名: {filename}")
    except Exception as e:
        print(f"\n--- 匯出 CSV 失敗 ---")
        print(f"錯誤: {e}")
# --- 运行主程序 ---
if __name__ == "__main__":
    analytics_df = run_ga4_engaged_sessions_by_source()

    if analytics_df is not None:
        print("\n--- 來源網站優質工作階段分析報告 ---")
        
        # 按 'sessionSource' 分組並計算總參與工作階段數
        source_summary = analytics_df.groupby('sessionSource').agg({
            'sessions': 'sum',
            'engagedSessions': 'sum'
        }).reset_index()
        
        # 計算每個來源的實際參與率
        source_summary['Calculated_Engagement_Rate'] = (source_summary['engagedSessions'] / source_summary['sessions']) * 100
        source_summary = source_summary.sort_values(by='engagedSessions', ascending=False)

        source_summary['Calculated_Engagement_Rate'] = source_summary['Calculated_Engagement_Rate'].round(2).astype(str) + '%'
        
        source_summary = source_summary.rename(columns={
            'sessionSource': '來源 ',
            'sessions': '總工作階段數',
            'engagedSessions': '參與工作階段數 (優質)',
        })

        print(source_summary.head(10).to_string(index=False))
export_to_csv(analytics_df)
    