<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Storage;

use Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

class RedisReader implements ReaderInterface
{

    const READER_NAME = 'redis-reader';

    /**
     * @var \Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadInterface
     */
    protected $redis;

    /**
     * @param \Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadInterface $redis
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
