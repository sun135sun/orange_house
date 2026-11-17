#!/bin/bash
set -e

echo "=== 更新系统包 ==="
sudo apt update

echo "=== 安装 FreeRADIUS + Redis ==="
sudo apt install -y freeradius freeradius-redis redis-server

echo "=== 启用 redis 模块 ==="
sudo ln -sf /etc/freeradius/3.0/mods-available/redis /etc/freeradius/3.0/mods-enabled/

echo "=== 配置 /etc/freeradius/3.0/sites-enabled/default（认证部分）==="
sudo sed -i '/authorize {/a \
    \
    # Redis username/password auth\
    if ("%{redis:hget user:%{User-Name} password}" != "") {\
        update control {\
            Cleartext-Password := "%{redis:hget user:%{User-Name} password}"\
        }\
    } else {\
        reject\
    }\
' /etc/freeradius/3.0/sites-enabled/default


echo "=== 安装 check_redis_limit.sh ==="
sudo tee /usr/local/bin/check_redis_limit.sh > /dev/null <<'EOF'
#!/bin/bash

USERNAME="$1"

USED=$(redis-cli HGET user:$USERNAME used)
LIMIT=$(redis-cli HGET user:$USERNAME limit)

USED=${USED:-0}
LIMIT=${LIMIT:-0}

if [ "$USED" -ge "$LIMIT" ]; then
    exit 1
else
    exit 0
fi
EOF

sudo chmod +x /usr/local/bin/check_redis_limit.sh


echo "=== 添加 check_redis_limit 模块 ==="
sudo tee /etc/freeradius/3.0/mods-enabled/check_redis_limit > /dev/null <<'EOF'
exec check_redis_limit {
    wait = yes
    program = "/usr/local/bin/check_redis_limit.sh %{User-Name}"
}
EOF


echo "=== 在 post-auth 阶段启用 check_redis_limit ==="
sudo sed -i '/post-auth {/a \
    check_redis_limit\
' /etc/freeradius/3.0/sites-enabled/default


echo "=== 安装 update_redis_traffic.sh ==="
sudo tee /usr/local/bin/update_redis_traffic.sh > /dev/null <<'EOF'
#!/bin/bash

USER="$1"
IN="$2"
OUT="$3"

REDIS_CLI="/usr/bin/redis-cli"
TOTAL=$((IN + OUT))

$REDIS_CLI HINCRBY user:$USER used $TOTAL >/dev/null 2>&1

exit 0
EOF

sudo chmod +x /usr/local/bin/update_redis_traffic.sh


echo "=== 添加 update_redis_traffic 模块 ==="
sudo tee /etc/freeradius/3.0/mods-enabled/update_redis_traffic > /dev/null <<'EOF'
exec update_redis_traffic {
    wait = yes
    program = "/usr/local/bin/update_redis_traffic.sh %{User-Name} %{Acct-Input-Octets} %{Acct-Output-Octets}"
    input_pairs = request
    output = none
}
EOF


echo "=== 在 accounting 部分插入流量统计 ==="
sudo sed -i '/accounting {/a \
    if (Acct-Status-Type == "Interim-Update") {\
        update_redis_traffic\
    }\
' /etc/freeradius/3.0/sites-enabled/default


echo "=== 创建 test 用户到 Redis ==="
redis-cli HMSET user:test password 123456 used 0 limit 100000000


echo "=== 测试脚本 test_acct.sh ==="
sudo tee test_acct.sh > /dev/null <<'EOF'
#!/bin/bash

echo "=== Sending Accounting-Start ==="
echo "User-Name=test
Acct-Status-Type=Start
Acct-Session-Id=12345
NAS-IP-Address=127.0.0.1
NAS-Port=0" | radclient -x 127.0.0.1:1813 acct testing123

echo "=== Sending Interim-Update ==="
echo "User-Name=test
Acct-Status-Type=Interim-Update
Acct-Session-Id=12345
NAS-IP-Address=127.0.0.1
NAS-Port=0
Acct-Input-Octets=500
Acct-Output-Octets=300" | radclient -x 127.0.0.1:1813 acct testing123

echo "=== Sending Accounting-Stop ==="
echo "User-Name=test
Acct-Status-Type=Stop
Acct-Session-Id=12345
NAS-IP-Address=127.0.0.1
NAS-Port=0
Acct-Input-Octets=500
Acct-Output-Octets=300" | radclient -x 127.0.0.1:1813 acct testing123

EOF

sudo chmod +x test_acct.sh

echo "=== 检查配置语法 ==="
sudo freeradius -CX || { echo "配置错误！"; exit 1; }

echo "=== 重启 FreeRADIUS ==="
sudo systemctl restart freeradius

echo "=== 完成！运行认证测试：==="
echo "  radtest test 123456 127.0.0.1 0 testing123"

echo "=== 运行计费测试：==="
echo "  sudo ./test_acct.sh"

