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
     * @param \Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface $redisFacade
     * @param int $redisPort
     */
    public function __construct(StorageRedisToRedisFacadeInterface $redisFacade, int $redisPort)
    {
        $this->redisFacade = $redisFacade;
        $this->redisPort = $redisPort;
    }

    /**
     * @param string $destination
     *
     * @return bool
     */
    public function export(string $destination): bool
    {
        return $this->redisFacade->export($destination, $this->redisPort);
    }
}
