設定 crontab 定時自動執行
vim /etc/crontab
#1.採集新影片
0 2 * * *   root /usr/local/sbin/get_res.sh >/tmp/get_res.log
#2.刪除敏感字
0 3 * * *   root /usr/local/sbin/vod_type_del.sh  >/dev/null 2>&1

  

 