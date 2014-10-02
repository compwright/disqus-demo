<?php

namespace compwright\Guzzle\Cache;

use Memcache;

class MemcacheFailoverStrategy
{
	protected $cache;
	protected $key;
	protected $expects;

	public function __construct(Memcache $cache, $key, $expects)
	{
		$this->cache = $cache;
		$this->key = $key;
		$this->expects = $expects;
	}

	public function __invoke()
	{
		return $this->cache->get($this->key) !== $this->expects;
	}
}
