<?php
// Constants
!defined('DS') && define('DS', DIRECTORY_SEPARATOR);
// 系统名称
!defined('APP_NAME') && define('APP_NAME', 'devtool');
// 基础根目录
!defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__));
// cli命名空间
!defined('COMMAND_NS') && define('COMMAND_NS', 'Swoft\Devtool\Command');

// 注册别名
$aliases = [
    '@root'       => BASE_PATH,
    '@app'        => '@root/app',
    '@res'        => '@root/resources',
    '@runtime'    => '@root/runtime',
    '@configs'    => '@root/config',
    '@resources'  => '@root/resources',
    '@beans'      => '@configs/beans',
    '@properties' => '@configs/properties',
    '@commands'   => '@app/Commands'
];
\Swoft\App::setAliases($aliases);
