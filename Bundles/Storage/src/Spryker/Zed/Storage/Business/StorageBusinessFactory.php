<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Storage\Business\Model\Storage;
use Spryker\Zed\Storage\Business\Model\StorageExporter;
use Spryker\Zed\Storage\Business\Model\StorageImporter;
use Spryker\Zed\Storage\StorageDependencyProvider;

/**
 * @method \Spryker\Zed\Storage\StorageConfig getConfig()
 */
class StorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Storage\Business\Model\StorageInterface
     */
    public function createStorage()
    {
        return new Storage(
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Zed\Storage\Business\Model\StorageExporterInterface
     */
    public function createStorageExporter()
    {
        return new StorageExporter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Storage\Business\Model\StorageImporterInterface
     */
    public function createStorageImporter()
    {
        return new StorageImporter($this->getConfig());
    }
}
