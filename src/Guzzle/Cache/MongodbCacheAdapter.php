<?php

namespace compwright\Guzzle\Cache;

use Guzzle\Cache\AbstractCacheAdapter;
use MongoCollection;
use MongoId;

class MongodbCacheAdapter extends AbstractCacheAdapter
{
    public function __construct(MongoCollection $cache, array $options = [])
    {
        $this->cache = $cache;
        $this->options = $options;
    }

    protected function getId($id)
    {
        // Hash ID to 24 hex chars for MongoDB
        $hash = substr(md5($id), 0, 24);
        return new MongoId($hash);
    }

    public function contains($id, array $options = null)
    {
        $query = [
            '_id' => $this->getId($id),
        ];

        return (bool) $this->cache->findOne($query, [], $this->options);
    }

    public function delete($id, array $options = null)
    {
        $query = [
            '_id' => $this->getId($id),
        ];

        $options = $this->options;
        $options['justOne'] = TRUE;
        $result = $this->cache->remove($query, $options);

        if (isset($options['w']))
        {
            return (
                isset($result['ok']) &&
                $result['ok'] &&
                empty($result['err']) &&
                isset($result['n']) &&
                $result['n'] > 0
            );
        }
        else
        {
            return $result;
        }
    }

    public function fetch($id, array $options = null)
    {
        $query = [
            '_id' => $this->getId($id),
        ];

        $result = $this->cache->findOne($query, [], $this->options);

        return $result
             ? $result['contents']
             : FALSE;
    }

    public function save($id, $data, $lifeTime = false, array $options = null)
    {
        try
        {
            $document = [
                '_id' => $this->getId($id),
                'contents' => $data,
            ];

            $result = $this->cache->insert($document, $this->options);
        }
        catch (\MongoDuplicateKeyException $e)
        {
            // @TODO: handle this the proper way with an upsert
            $query = [
                '_id' => $this->getId($id),
            ];

            $document = [
                'contents' => $data,
            ];

            $result = $this->cache->update($query, $document, $this->options);
        }

        if (isset($this->options['w']))
        {
            return (
                isset($result['ok']) &&
                $result['ok'] &&
                empty($result['err'])
            );
        }
        else
        {
            return $result;
        }
    }
}
