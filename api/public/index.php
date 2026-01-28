<?php

use Config\Bootstrap;
use Neoan\Helper\Env;
use Neoan\NeoanApp;
use Neoan\Routing\AttributeRouting;

require_once '../vendor/autoload.php';

$appPath = dirname(__DIR__) . '/src';
$publicPath = __DIR__;
$cliPath = dirname(__DIR__);

$setup = new \Neoan\Helper\Setup();
$setup->setPublicPath($publicPath)->setLibraryPath($appPath);

$cors = new Neoan\Cors\Cors();
$cors->addAllowedOrigin('*')
    ->setAllowedMethods(['GET','OPTIONS', 'POST', 'PUT', 'PATCH', 'HEAD', 'DELETE'])
    ->setAllowedHeaders(['Content-Type', 'X-Auth-Token', 'X-Requested-With', 'Authorization', 'Origin', 'Accept', 'Referrer', 'X-SignMe-HMAC-SHA256']);

$app = new NeoanApp($setup, $cliPath);
$app->invoke($cors);
$app->invoke(new AttributeRouting('App'));
new Bootstrap($app);

$app->run();