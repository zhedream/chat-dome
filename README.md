# myFrame
我的一个简单的MVC框架
## 自动加载

## 核心类

## DB类

## Route 类
2018年8月31日 v0.1.1

2018年9月9日 v1.0.0
    注册路由->链式 命名路由->生产URL

## 核心控制器
    MockController 用于模拟数据
    QueueController 处理消息队列
    StaticController 处理静态化
# 目录结构
    app/
        controllers/ -> 存放控制器
            --核心控制器
            Controller.php              -> 被 所有控制器 继承
            TempController.php      -> 控制器模板
            
        models/ 存放模型
            --核心控制器
            Model.php              -> 被 所有模型 继承
            Temp.php    -> 模型模板
    Config/
        config.php      -> 配置文件
    core/ 核心代码
        Controller.php  -> 核心控制器
        App.php         -> 入口文件整合
        corefn.php      -> 全局核心辅助函数
        DB.php          -> 数据库类 单例
        Loader.php      -> 自动加载类
        Model.php       -> 核心模型
        RD.php          -> redis类 队列/缓存
        Request.php     -> 处理请求类  待完善 目前没卵用
        Response.php    -> 相应类 待完善 目前 没什么用
        Route.php       -> 路由
    libs/ 拓展类
        Log.php         -> 日志类
        Mail.php        -> 邮件类
        Snowflake.php   -> 生成雪花编号
        Upload.php      -> 上传类
    logs/ 日志文件夹
    public/ 
        入口文件&前端页面&资源
        css/            -> 样式文件
        images/         -> 图片文件
        js/             -> JS文件
        index.php       -> 入口文件
        .htaccess       -> 路由重写文件
    route/
        web.php         -> 注册路由
    tests/ 存放一些PHP测试文件 与 实例 代码
        MockController.php      -> 用于模拟数据
        QueueController.php     -> 处理消息队列
        StaticController.php    -> 处理静态化
        AlipayController.php    -> 支付宝支付
        ... 
    uploads/ 用户上传资源
    verdor/ composer
    views/ 视图资源

    composer.json       -> 使用的包
    .gitignore          -> git 忽略文件
    artisna.php         -> 命令行指令文件
    env.php             -> 开发配置

## Exception 异常 与 捕获定位


## 数据格式输出

## 框架优化 
1. 路由优化 注册路由

##  myFrame v1.0

2018年9月25日 10点08分

## project-shop 
2018年9月25日 BEGING

## myFrame v2.0

## project-websocket

2018年11月12日 完成 dome 


## END
