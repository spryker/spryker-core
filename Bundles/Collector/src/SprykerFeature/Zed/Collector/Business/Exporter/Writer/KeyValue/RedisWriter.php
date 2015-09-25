<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;

use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadWriteInterface;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\RedisReadWrite;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class RedisWriter implements WriterInterface
{

    /**
     * @var ReadWriteInterface|RedisReadWrite
     */
    protected $redis;

    /**
     * @param ReadWriteInterface $kvAdapter
     */
    public function __construct(ReadWriteInterface $kvAdapter)
    {
        $this->redis = $kvAdapter;
    }

    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        return $this->redis->setMulti($dataSet);
    }

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function delete(array $dataSet)
    {
        $dataSetAssociate = [];
        foreach ($dataSet as $redisKey) {
            $dataSetAssociate[$redisKey] = true;
        }

        return $this->redis->deleteMulti($dataSetAssociate);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'redis-writer';
    }

}
