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
     * @param string|null $redisHost
     *
     * @return bool
     */
    public function export(string $destination, int $redisPort, ?string $redisHost = null): bool
    {
        return $this->redisFacade->export($destination, $redisPort, $redisHost);
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
