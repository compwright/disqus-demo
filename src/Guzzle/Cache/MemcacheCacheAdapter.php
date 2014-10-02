<?php

namespace compwright\Guzzle\Cache;

use Guzzle\Cache\AbstractCacheAdapter;
use Memcache;

class MemcacheCacheAdapter extends AbstractCacheAdapter
{
    protected $cache;

    public function __construct(Memcache $cache, array $options = [])
    {
        $this->cache = $cache;
        $this->flags = isset($options['flags']) ? $options['flags'] : 0;
        $this->ttl = isset($options['ttl']) ? $options['ttl'] : 30;
    }

    public function contains($id, array $options = null)
    {
        return $this->cache->get($id, $this->flags) !== FALSE;
    }

    public function delete($id, array $options = null)
    {
        return $this->cache->delete($id);
    }

    public function fetch($id, array $options = null)
    {
        return $this->cache->get($id, $this->flags);
    }

    public function save($id, $data, $lifeTime = false, array $options = null)
    {
        return $this->cache->set($id, $data, $this->flags, $this->ttl);
    }
}
