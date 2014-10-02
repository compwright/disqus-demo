#!/usr/bin/env php
<?php

namespace compwright\DisqusDemo;

require_once 'bootstrap.php';

use compwright\Disguz\Disguz;
use Guzzle\Http\Exception\HttpException;
use Aura\Cli\CliFactory;
use Aura\Cli\Status;
use Memcache;

$factory = new CliFactory;
$stdio = $factory->newStdio();

// Create a client and pass an array of configuration data
$client = Disguz::factory([
	'disqus.keys' => [
		'api_key' => getenv('DISQUS_API_KEY'),
	],
]);

// Connect to burst cache
$memcache = new Memcache;
list($host, $port) = explode(':', getenv('MEMCACHED'));
$connected = $memcache->connect($host, $port);
if ( ! $connected)
{
	throw new \Exception('Unable to connect to Memcache server, ' . getenv('MEMCACHED'));
}

$forum = getenv('DISQUS_FORUM');
$ttl = getenv('CACHE_TTL');
$healthyKey = getenv('CACHE_NAMESPACE') . '-healthy';

try
{
	// Ping the Disqus API
	$client->threadsList(compact('forum'));

	// Save healthy status in burst cache
	$memcache->set($healthyKey, 'healthy', 0, $ttl);

	// Report status to the console
	$stdio->outln('healthy');
	exit(Status::SUCCESS);
}
catch (HttpException $e)
{
	// Remove healthy status from burst cache
	// @TODO: implement exponential backoff
	$memcache->delete($healthyKey);

	// Report status to the console
	$stdio->errln('unhealthy');
	exit(Status::FAILURE);
}
