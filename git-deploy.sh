#!/bin/bash
# 
# -----------------
# Git 项目自动部署脚本
#
# @author Carlos <anzhengchao@gmail.com>
# @link   https://github.com/overtrue
# 
# -----------------
#
# 当前脚本路径
BASEDIR=$(dirname $(readlink -f $0)) 

WWW_DIR=你的www目录
WWW_USER=你的www用户名
WWW_GROUP=你的www用户组

REPO_URL=giturl
REPO_NAME=就是这个项目在www目录下的目录名,比如：www/laravel，就写上laravel

cd $WWW_DIR


if [[ -d "$REPO_NAME" ]]; then
    echo "$REPO_NAME 存在，git pull..."
    cd $REPO_NAME && git pull 
    if [[ $? -eq 0 ]]; then
        echo "更新完成"
    else 
        echo "更新出错！"
    fi
else
    echo "$REPO_NAME 不存在，git clone..." 
    git clone $REPO_URL $REPO_NAME
fi

if [[ $? -eq 0 ]]; then
    echo "修改用户组:$WWW_USER:$WWW_GROUP"
    chown -R $WWW_USER:$WWW_GROUP $WWW_DIR
fi

echo "项目部署完毕！^_^"

cd $BASEDIR
