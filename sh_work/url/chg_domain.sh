#!/bin/bash

# ===============================================
# 新增的检查和安装 pwgen 逻辑 (Ubuntu 20.04)
# ===============================================
echo "检查系统是否安装了 'pwgen'..."

# 使用 dpkg-query 检查 pwgen 是否已安装
if ! dpkg-query -W -f='${Status}' pwgen 2>/dev/null | grep -q "install ok installed"; then
    echo "'pwgen' 未安装，正在尝试安装..."
    
    # 更新包列表
    sudo apt update
    
    # 安装 pwgen
    if sudo apt install -y pwgen; then
        echo "'pwgen' 已成功安装。"
    else
        echo "错误：无法安装 'pwgen'。请手动运行 'sudo apt install pwgen' 并检查错误。"
        exit 1 # 安装失败则退出脚本
    fi
else
    echo "'pwgen' 已安装。"
fi
# ===============================================
# 創建發布頁
# ===============================================

#配置根域名
dname=ggmimicc

echo $dname


# 生成一个1到7之间的随机数作为第一个字符串的长度
len1=4

# 第二个字符串的长度为12减去第一个字符串的长度减去2个分隔符的长度
len2=3

# 使用pwgen生成指定长度的小写字母和数字的字符串
str1=$(pwgen -0 -A $len1 1)
str2=$(pwgen -0 -A $len2 1)
str3=$(pwgen -0 -A $len1 1)

# 拼接结果
sub="${str1}-${str2}-${str3}"
echo "產生的字串: ${sub}"

fname="index.html"
rm -rf ${fname}
cp index.html_template ${fname}



echo "sed -i  's/"TDname"/${dname}/' ${fname}" > /tmp/c.sh
echo "sed -i  's/"TSub"/${sub}/' ${fname}" >> /tmp/c.sh


sh /tmp/c.sh

grep sub_str ${fname}


