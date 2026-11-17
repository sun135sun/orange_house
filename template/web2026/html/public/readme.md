{maccms:type ids="parent" flag="vod" order="asc" by="sort" id="vo" key="key2"} 
SELECT 
    type_id,
    type_name, 
    type_pid,
    type_mid,
    type_sort,
    type_status,
    type_enname,
    type_key,
    type_des
FROM mac_type 
WHERE type_mid = 1           -- flag="vod" 影片模塊
  AND type_pid = 0           -- ids="parent" 頂級分類
  AND type_status = 1        -- 只查啟用狀態
ORDER BY type_sort ASC;     
 
{maccms:type parent="'. $vo['type_id'] .'" flag="vod" order="asc" by="sort" id="vo2" key="key2"}
SELECT * 
FROM mac_type 
WHERE type_mid = 1 
  AND type_pid = [父分類ID] 
  AND type_status = 1 
ORDER BY type_sort ASC;