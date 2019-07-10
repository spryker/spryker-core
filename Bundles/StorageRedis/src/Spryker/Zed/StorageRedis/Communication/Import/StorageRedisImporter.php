<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Communication\Import;

use Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface;

class StorageRedisImporter implements StorageRedisImporterInterface
{
    /**
     * @var \Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface
     */
    protected $redisFacade;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @param \Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface $redisFacade
     * @param string $destination
     */
    public function __construct(StorageRedisToRedisFacadeInterface $redisFacade, string $destination)
    {
        $this->redisFacade = $redisFacade;
        $this->destination = $destination;
    }

    /**
     * @param string $source
     *
     * @return bool
     */
    public function import(string $source): bool
    {
        return $this->redisFacade->import($source, $this->destination);
    }
}
