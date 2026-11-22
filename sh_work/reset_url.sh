
#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH

rooturl="http://www.orangeh.top"
curl -k --connect-timeout 10 -m 3600 "${rooturl}/api.php/link/reset_pc"
curl -k --connect-timeout 10 -m 3600 "${rooturl}/api.php/link/reset"