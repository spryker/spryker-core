<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Communication\Export;

use Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface;

class StorageRedisExporter implements StorageRedisExporterInterface
{
    /**
     * @var \Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface
     */
    protected $redisFacade;

    /**
     * @var int
     */
    protected $redisPort;

    /**
     * @var string
     */
    protected $redisHost;

    /**
     * @param \Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface $redisFacade
     * @param int $redisPort
     * @param string $redisHost
     */
    public function __construct(StorageRedisToRedisFacadeInterface $redisFacade, int $redisPort, string $redisHost)
    {
        $this->redisFacade = $redisFacade;
        $this->redisPort = $redisPort;
        $this->redisHost = $redisHost;
    }

    /**
     * @param string $destination
     *
     * @return bool
     */
    public function export(string $destination): bool
    {
        return $this->redisFacade->export(
            $destination,
            $this->redisPort,
            $this->redisHost
        );
    }
}
