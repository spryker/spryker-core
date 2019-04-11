<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Dependency\Facade;

class StorageRedisToRedisFacadeBridge implements StorageRedisToRedisFacadeInterface
{
    /**
     * @var \Spryker\Zed\Redis\Business\RedisFacadeInterface
     */
    protected $redisFacade;

    /**
     * @param \Spryker\Zed\Redis\Business\RedisFacadeInterface $redisFacade
     */
    public function __construct($redisFacade)
    {
        $this->redisFacade = $redisFacade;
    }

    /**
     * @param string $destination
     * @param int $redisPort
     *
     * @return bool
     */
    public function export(string $destination, int $redisPort): bool
    {
        return $this->redisFacade->export($destination, $redisPort);
    }

    /**
     * @param string $source
     * @param string $destination
     *
     * @return bool
     */
    public function import(string $source, string $destination): bool
    {
        return $this->redisFacade->import($source, $destination);
    }
}
