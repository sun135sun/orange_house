#!/bin/bash

# SoftEther VPN Docker 完整安装脚本
# 包含 Docker 自动安装和 VPN 配置

set -e

# 颜色定义
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# 默认配置
DEFAULT_IP=$(hostname -I | awk '{print $1}')
DEFAULT_PASSWORD="$(date +%s | sha256sum | base64 | head -c 16)"
DEFAULT_HUB="VPNHUB"
DEFAULT_USER="vpnuser"
DEFAULT_USER_PW="$(openssl rand -base64 12)"

# 显示标题
echo -e "${BLUE}"
echo "=========================================="
echo "    SoftEther VPN 完整安装脚本"
echo "=========================================="
echo -e "${NC}"

# 函数：安装 Docker
install_docker_if_needed() {
    if command -v docker &> /dev/null; then
        echo -e "${GREEN}✓ Docker 已安装${NC}"
        docker --version
        return 0
    fi
    
    echo -e "${YELLOW}Docker 未安装，开始自动安装...${NC}"
    
    # 自动安装 Docker
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
    
    # 添加用户到 docker 组
    sudo usermod -aG docker $USER
    
    # 启动 Docker 服务
    sudo systemctl start docker
    sudo systemctl enable docker
    
    echo -e "${GREEN}✓ Docker 安装完成${NC}"
    
    # 提示用户需要重新登录
    echo -e "${YELLOW}注意: 需要重新登录或执行 'newgrp docker' 才能使用 Docker 命令${NC}"
    echo -e "${YELLOW}现在将尝试继续安装，如果遇到权限错误，请重新运行脚本${NC}"
    
    # 等待一下让服务完全启动
    sleep 3
}

# 函数：输入配置参数
input_config() {
    echo -e "${YELLOW}配置 SoftEther VPN 参数${NC}"
    
    # 获取本机IP
    LOCAL_IP=$(hostname -I | awk '{print $1}')
    
    read -p "输入服务器IP地址 [默认: $LOCAL_IP]: " SERVER_IP
    SERVER_IP=${SERVER_IP:-$LOCAL_IP}
    
    read -p "输入管理员密码 [默认: 自动生成]: " ADMIN_PASSWORD
    if [ -z "$ADMIN_PASSWORD" ]; then
        ADMIN_PASSWORD=$(date +%s | sha256sum | base64 | head -c 16)
        echo -e "${GREEN}生成密码: $ADMIN_PASSWORD${NC}"
    fi
    
    read -p "输入虚拟HUB名称 [默认: $DEFAULT_HUB]: " HUB_NAME
    HUB_NAME=${HUB_NAME:-$DEFAULT_HUB}
    
    read -p "输入VPN用户名 [默认: $DEFAULT_USER]: " VPN_USER
    VPN_USER=${VPN_USER:-$DEFAULT_USER}
    
    read -p "输入VPN用户密码 [默认: 自动生成]: " VPN_USER_PW
    if [ -z "$VPN_USER_PW" ]; then
        VPN_USER_PW=$(openssl rand -base64 12)
        echo -e "${GREEN}生成密码: $VPN_USER_PW${NC}"
    fi
    
    # 保存配置到文件
    cat > softether-config.env << EOF
SERVER_IP=$SERVER_IP
ADMIN_PASSWORD=$ADMIN_PASSWORD
HUB_NAME=$HUB_NAME
VPN_USER=$VPN_USER
VPN_USER_PW=$VPN_USER_PW
EOF
    
    echo -e "${GREEN}配置已保存到 softether-config.env${NC}"
}

# 函数：加载配置
load_config() {
    if [ -f "softether-config.env" ]; then
        source softether-config.env
        echo -e "${GREEN}已加载现有配置${NC}"
    else
        echo -e "${YELLOW}未找到配置文件，使用默认值${NC}"
        SERVER_IP=$(hostname -I | awk '{print $1}')
        ADMIN_PASSWORD=$DEFAULT_PASSWORD
        HUB_NAME=$DEFAULT_HUB
        VPN_USER=$DEFAULT_USER
        VPN_USER_PW=$DEFAULT_USER_PW
    fi
}

# 函数：配置防火墙
setup_firewall() {
    echo -e "${YELLOW}配置防火墙规则...${NC}"
    
    # 检查 ufw 是否安装
    if command -v ufw &> /dev/null; then
        sudo ufw allow 500/udp comment "SoftEther IPsec IKE"
        sudo ufw allow 4500/udp comment "SoftEther IPsec NAT-T"
        sudo ufw allow 1701/tcp comment "SoftEther L2TP"
        sudo ufw allow 1701/udp comment "SoftEther L2TP"
        sudo ufw allow 1194/udp comment "SoftEther OpenVPN"
        sudo ufw allow 443/tcp comment "SoftEther VPN"
        sudo ufw allow 992/tcp comment "SoftEther VPN"
        sudo ufw allow 5555/tcp comment "SoftEther Management"
        echo -e "${GREEN}防火墙规则已添加${NC}"
    else
        echo -e "${YELLOW}ufw 未安装，跳过防火墙配置${NC}"
    fi
}

# 函数：安装 SoftEther VPN
install_softether() {
    echo -e "${YELLOW}开始安装 SoftEther VPN...${NC}"
    
    load_config
    
    # 创建数据目录
    sudo mkdir -p /data/softether
    
    # 停止并删除现有容器
    if docker ps -a | grep -q softether-vpn; then
        echo -e "${YELLOW}停止并删除现有容器...${NC}"
        docker stop softether-vpn || true
        docker rm softether-vpn || true
    fi
    
    # 拉取 Docker 镜像
    echo -e "${YELLOW}拉取 SoftEther VPN Docker 镜像...${NC}"
    docker pull siomiz/softethervpn
    
    # 运行容器
    echo -e "${YELLOW}启动 SoftEther VPN 容器...${NC}"
    docker run -d \
        --name softether-vpn \
        --restart unless-stopped \
        --cap-add NET_ADMIN \
        --device /dev/net/tun \
        -p 500:500/udp \
        -p 4500:4500/udp \
        -p 1701:1701/tcp \
        -p 1701:1701/udp \
        -p 1194:1194/udp \
        -p 5555:5555/tcp \
        -p 443:443/tcp \
        -p 992:992/tcp \
        -e PSK=$ADMIN_PASSWORD \
        -e SPW=$ADMIN_PASSWORD \
        -e HPW=$ADMIN_PASSWORD \
        -e USERS=$VPN_USER:$VPN_USER_PW \
        siomiz/softethervpn
    
    # 等待容器启动
    echo -e "${YELLOW}等待容器启动...${NC}"
    sleep 15
    
    # 显示容器状态
    echo -e "${GREEN}SoftEther VPN 安装完成!${NC}"
    show_status
}

# 函数：显示状态
show_status() {
    echo -e "${YELLOW}=== SoftEther VPN 状态 ===${NC}"
    
    if docker ps | grep -q softether-vpn; then
        echo -e "${GREEN}✓ SoftEther VPN 容器正在运行${NC}"
        
        # 显示容器详细信息
        echo -e "\n${YELLOW}容器信息:${NC}"
        docker ps -f name=softether-vpn
        
        echo -e "\n${YELLOW}容器日志 (最后5行):${NC}"
        docker logs --tail 5 softether-vpn
        
        load_config
        echo -e "\n${YELLOW}连接信息:${NC}"
        echo "服务器IP: $SERVER_IP"
        echo "管理员密码: $ADMIN_PASSWORD"
        echo "虚拟HUB: $HUB_NAME"
        echo "VPN用户: $VPN_USER"
        echo "VPN用户密码: $VPN_USER_PW"
        echo -e "\n${YELLOW}开放端口:${NC}"
        echo "L2TP/IPsec: UDP 500, 4500, 1701"
        echo "OpenVPN: UDP 1194"
        echo "SoftEther VPN: TCP 443, 992, 5555"
        
    else
        echo -e "${RED}✗ SoftEther VPN 容器未运行${NC}"
        echo -e "${YELLOW}尝试启动容器...${NC}"
        docker start softether-vpn
        sleep 5
        show_status
    fi
}

# 函数：测试 VPN 连接
test_vpn() {
    echo -e "${YELLOW}开始测试 VPN 连接...${NC}"
    
    load_config
    
    # 检查容器状态
    if ! docker ps | grep -q softether-vpn; then
        echo -e "${RED}错误: SoftEther VPN 容器未运行${NC}"
        exit 1
    fi
    
    echo -e "${GREEN}✓ VPN 容器正在运行${NC}"
    
    # 测试端口连通性
    echo -e "\n${YELLOW}测试端口连通性:${NC}"
    
    # 使用 nc 或 telnet 测试端口
    for port in 443 992 5555; do
        if nc -z -w 3 $SERVER_IP $port 2>/dev/null; then
            echo -e "${GREEN}✓ 端口 $port/tcp 开放${NC}"
        else
            echo -e "${RED}✗ 端口 $port/tcp 关闭${NC}"
        fi
    done
    
    # 测试容器内部服务
    echo -e "\n${YELLOW}测试容器内部服务:${NC}"
    if docker exec softether-vpn pgrep vpnserver > /dev/null; then
        echo -e "${GREEN}✓ vpnserver 进程正在运行${NC}"
    else
        echo -e "${RED}✗ vpnserver 进程未运行${NC}"
    fi
    
    echo -e "\n${GREEN}测试完成!${NC}"
    create_client_config
}

# 函数：创建客户端配置文件
create_client_config() {
    load_config
    
    echo -e "${YELLOW}创建客户端配置文件...${NC}"
    
    # L2TP/IPsec 配置
    cat > l2tp-ipsec-config.txt << EOF
=== L2TP/IPsec 配置 ===
服务器: $SERVER_IP
用户名: $VPN_USER
密码: $VPN_USER_PW
预共享密钥: $ADMIN_PASSWORD
协议: L2TP/IPsec

连接步骤:
1. 打开网络设置
2. 添加 VPN 连接
3. 选择 L2TP/IPsec
4. 填写以上信息
EOF

    # 显示连接信息
    cat > vpn-connection-info.txt << EOF
=== SoftEther VPN 连接信息 ===
服务器地址: $SERVER_IP

=== L2TP/IPsec 配置 ===
用户名: $VPN_USER
密码: $VPN_USER_PW
预共享密钥: $ADMIN_PASSWORD

=== OpenVPN 配置 ===
端口: 1194 (UDP)
用户名: $VPN_USER
密码: $VPN_USER_PW

=== 管理界面 ===
地址: https://$SERVER_IP:5555
密码: $ADMIN_PASSWORD

生成时间: $(date)
EOF

    echo -e "${GREEN}客户端配置文件已创建:${NC}"
    echo "- l2tp-ipsec-config.txt"
    echo "- vpn-connection-info.txt"
    echo ""
    echo -e "${YELLOW}重要的连接信息已保存到文件，请妥善保管!${NC}"
}

# 函数：完整安装流程
full_installation() {
    echo -e "${BLUE}开始完整安装流程...${NC}"
    
    # 1. 检查并安装 Docker
    install_docker_if_needed
    
    # 2. 输入配置
    input_config
    
    # 3. 配置防火墙
    setup_firewall
    
    # 4. 安装 SoftEther VPN
    install_softether
    
    # 5. 测试连接
    test_vpn
    
    echo -e "${GREEN}完整安装流程完成!${NC}"
}

# 主菜单
main_menu() {
    while true; do
        echo -e "\n${BLUE}SoftEther VPN 管理菜单${NC}"
        echo "1. 完整安装 (推荐)"
        echo "2. 仅安装 Docker"
        echo "3. 配置 VPN 参数"
        echo "4. 安装 SoftEther VPN"
        echo "5. 测试 VPN 连接"
        echo "6. 查看状态"
        echo "7. 创建客户端配置"
        echo "8. 退出"
        read -p "请选择操作 [1-8]: " choice
        
        case $choice in
            1)
                full_installation
                ;;
            2)
                install_docker_if_needed
                ;;
            3)
                input_config
                ;;
            4)
                install_softether
                ;;
            5)
                test_vpn
                ;;
            6)
                show_status
                ;;
            7)
                create_client_config
                ;;
            8)
                echo -e "${GREEN}再见!${NC}"
                exit 0
                ;;
            *)
                echo -e "${RED}无效选择，请重新输入${NC}"
                ;;
        esac
    done
}

# 设置脚本权限
chmod +x "$0"

# 启动主菜单
main_menu