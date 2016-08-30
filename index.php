<?php
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

// 定义应用目录
define('APP_PATH',dirname(__FILE__).'/');

//绑定模块
define('BIND_MODULE', 'Home');

//初始化框架
require APP_PATH.'./ZdcPHP/init.php';