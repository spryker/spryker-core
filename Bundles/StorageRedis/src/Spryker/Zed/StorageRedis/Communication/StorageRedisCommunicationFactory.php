<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StorageRedis\Communication\Export\StorageRedisExporter;
use Spryker\Zed\StorageRedis\Communication\Export\StorageRedisExporterInterface;
use Spryker\Zed\StorageRedis\Communication\Import\StorageRedisImporter;
use Spryker\Zed\StorageRedis\Communication\Import\StorageRedisImporterInterface;
use Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface;
use Spryker\Zed\StorageRedis\StorageRedisDependencyProvider;

/**
 * @method \Spryker\Zed\StorageRedis\StorageRedisConfig getConfig()
 */
class StorageRedisCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StorageRedis\Communication\Import\StorageRedisImporterInterface
     */
    public function createStorageRedisImporter(): StorageRedisImporterInterface
    {
        return new StorageRedisImporter(
            $this->getRedisFacade(),
            $this->getConfig()->getRdbDumpPath()
        );
    }

    /**
     * @return \Spryker\Zed\StorageRedis\Communication\Export\StorageRedisExporterInterface
     */
    public function createStorageRedisExporter(): StorageRedisExporterInterface
    {
        return new StorageRedisExporter(
            $this->getRedisFacade(),
            $this->getConfig()->getRedisPort()
        );
    }

    /**
     * @return \Spryker\Zed\StorageRedis\Dependency\Facade\StorageRedisToRedisFacadeInterface
     */
    public function getRedisFacade(): StorageRedisToRedisFacadeInterface
    {
        return $this->getProvidedDependency(StorageRedisDependencyProvider::FACADE_REDIS);
    }
}
