<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Collector\Code\Reader\KeyValue;

use Spryker\Shared\Collector\Code\Reader\ReaderInterface;
use Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadWriteInterface;

class RedisReader implements ReaderInterface
{

    /**
     * @var \Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadInterface
     */
    protected $redis;

    /**
     * @param \Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadWriteInterface $kvAdapter
     */
    public function __construct(ReadWriteInterface $kvAdapter)
    {
        $this->redis = $kvAdapter;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function read($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'redis-reader';
    }

}
