<?php

use Guzzle\Plugin\Cache\CachePlugin;
use Guzzle\Plugin\Cache\DefaultCacheStorage;
use Guzzle\Plugin\Cache\CallbackCanCacheStrategy;
use compwright\Guzzle\Cache\FailoverCacheAdapter;
use compwright\Guzzle\Cache\MemcacheCacheAdapter;
use compwright\Guzzle\Cache\MongodbCacheAdapter;
use compwright\Guzzle\Cache\MemcacheFailoverStrategy;

// Connect to burst cache
$memcache = new Memcache;
list($host, $port) = explode(':', getenv('MEMCACHED'));
$connected = $memcache->connect($host, $port);
if ( ! $connected)
{
	throw new \Exception('Unable to connect to Memcache server, ' . getenv('MEMCACHED'));
}

// Connect to failover cache
$mongo = new MongoClient('mongodb://' . getenv('MONGODB'));
$mongoCollection = $mongo
	->selectDB(getenv('CACHE_NAMESPACE'))
    ->selectCollection('failover');

$ttl = getenv('CACHE_TTL');
$healthyKey = getenv('CACHE_NAMESPACE') . '-healthy';

return new CachePlugin([
	// 10 years (basically forever) - memcache will get a different TTL
    'cache.override_ttl' => 60 * 60 * 24 * 365 * 10,

    'storage' => new DefaultCacheStorage(
        new FailoverCacheAdapter(
            new MemcacheCacheAdapter($memcache, compact('ttl')), // burst TTL
            new MongodbCacheAdapter($mongoCollection),
            new MemcacheFailoverStrategy($memcache, $healthyKey, 'healthy')
        )
    ),
]);
