<?php

date_default_timezone_set('America/New_York');

define('PROJECT_ROOT', __DIR__);

require_once 'vendor/autoload.php';

use Slim\Slim;

Dotenv::load(__DIR__);
Dotenv::required([
	'DISQUS_FORUM',
	'DISQUS_API_KEY',
	'DISQUS_API_SECRET',
	'DISQUS_ACCESS_TOKEN',
	'DISQUS_GUEST_KEY',
	'CACHE_NAMESPACE',
	'CACHE_SHORT_EXP',
	'MONGODB_ADDR',
	'MONGODB_PORT',
	'MEMCACHED_ADDR',
	'MEMCACHED_PORT',
]);

return new Slim([
	'templates.path' => __DIR__ . '/views',
]);
