<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Storage;

use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadInterface;

class RedisReader implements ReaderInterface
{
    public const READER_NAME = 'redis-reader';

    /**
     * @var \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadInterface
     */
    protected $redis;

    /**
     * @param \Spryker\Zed\Collector\Business\Storage\Adapter\KeyValue\ReadInterface $redis
     */
    public function __construct(ReadInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return string
     */
    public function read($key, $type = '')
    {
        return $this->redis->get($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::READER_NAME;
    }
}
