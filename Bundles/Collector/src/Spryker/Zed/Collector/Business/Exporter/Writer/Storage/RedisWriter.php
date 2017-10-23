<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Storage;

use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadWriteInterface;

class RedisWriter implements WriterInterface
{
    /**
     * @var \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadWriteInterface|\Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\RedisReadWrite
     */
    protected $redis;

    /**
     * @param \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadWriteInterface $kvAdapter
     */
    public function __construct(ReadWriteInterface $kvAdapter)
    {
        $this->redis = $kvAdapter;
    }

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function write(array $dataSet)
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
        return $this->redis->deleteMulti($dataSet);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'redis-writer';
    }
}
