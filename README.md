# chat-dome

## 项目介绍
一个 简单的 PHP 在线聊天系统

1. web 服务 使用 个人版学习 MVP 框架 （myFrame v2.0）
2. 前端 简陋的聊天室 嵌套使用 Vue 
3. socket 服务  使用 workerman

## 实现功能
1. 用户登陆注册
2. 简陋的后台管理
3. 简单的群聊 与 私聊

# 目录结构
    app/
        controllers/ -> 存放控制器
            IndexController.php     // web 服务 主要控制器
    public/
        js/             -> JS文件   **chat-dome 主要JS代码目录**
            app.js      -> 
            MyWebSocket.class.js    -> 封装 WebSocket  类
            WebSocket.js    -> 前端 socket 交互 主要文件

        index.php       -> 入口文件
        .htaccess       -> 路由重写文件
    route/
        web.php         -> 注册路由
    verdor/ composer
    views/ 视图资源

    composer.json       -> 使用的包
    .gitignore          -> git 忽略文件
    artisna.php         -> 命令行指令文件
    env.php             -> 开发配置
    **start.php**           -> web Socket 聊天服务器   **chat-dome 主要代码文件**

## 主要文件
route/web.php  //web 服务路由
start.php 
js/app.js
js/webSocket.js
js/myWebSocket.class.js

## 开始使用
在根目录建立 logs 目录
复制  env.php.example -> env.php
修改 js/webSocket.js     url: "ws://192.168.13.89:2347",  ipconfig   你的 IPv4 地址 . . . . . . . . . . . . : 192.168.13.89
1. composer install
修改 artisan.php  serve 项   IPv4 地址
2. php artisan.php serve  启动 web 服务  php -S 0.0.0.0:9999 -t public/
3. php start.php          启动聊天服务

浏览器打开

http://192.168.13.89:9999

http://192.168.13.89:9999/user





## END
