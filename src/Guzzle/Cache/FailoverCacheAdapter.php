<?php

namespace compwright\Guzzle\Cache;

use Guzzle\Cache\AbstractCacheAdapter;

class FailoverCacheAdapter extends AbstractCacheAdapter
{
    protected $burstCache;
    protected $failoverCache;
    protected $failoverStrategy;

    public function __construct(AbstractCacheAdapter $burstCache, AbstractCacheAdapter $failoverCache, $failoverStrategy)
    {
        $this->burstCache = $burstCache;
        $this->failoverCache = $failoverCache;

        if (is_callable($failoverStrategy))
        {
            $this->failoverStrategy = $failoverStrategy;
        }
        else
        {
            throw new \InvalidArgumentException('$failoverStrategy must be callable');
        }
    }

    public function contains($id, array $options = null)
    {
        $result = $this->burstCache->contains($id, $options);
        if ($result === FALSE && call_user_func($this->failoverStrategy))
        {
            $result = $this->failoverCache->contains($id, $options);
        }

        return $result;
    }

    public function delete($id, array $options = null)
    {
        return (
            $this->burstCache->delete($id, $options) &&
            $this->failoverCache->delete($id, $options)
        );
    }

    public function fetch($id, array $options = null)
    {
        $result = $this->burstCache->fetch($id, $options);
        if ($result === FALSE && call_user_func($this->failoverStrategy))
        {
            var_dump('Using failover cache');
            return $this->failoverCache->fetch($id, $options);
        }

        var_dump('Using burst cache');
        return $result;
    }

    public function save($id, $data, $lifeTime = false, array $options = null)
    {
        return (
            $this->burstCache->save($id, $data, $lifeTime, $options) &&
            $this->failoverCache->save($id, $data, $lifeTime, $options)
        );        
    }
}
