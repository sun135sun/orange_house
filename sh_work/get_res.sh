#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH

rooturl="http://www.orangeh.top"



# curl -k -sS --connect-timeout 10 -m 3600 "${rooturl}/api.php/timming/index.html?enforce=1&name=jkun"
# curl -k -sS --connect-timeout 10 -m 3600 "${rooturl}/api.php/timming/index.html?enforce=1&name=jkunsp"
curl -k -sS --connect-timeout 10 -m 3600 "${rooturl}/api.php/timming/index.html?name=ask"
curl -k -sS --connect-timeout 10 -m 3600 "${rooturl}/api.php/timming/index.html?enforce=1&name=naix"
curl -k -sS --connect-timeout 10 -m 3600 "${rooturl}/api.php/timming/index.html?enforce=1&name=yt"