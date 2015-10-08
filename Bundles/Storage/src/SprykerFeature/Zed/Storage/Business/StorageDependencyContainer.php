<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Storage\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\StorageBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Client\Storage\Service\StorageClient;
use SprykerFeature\Zed\Storage\Business\Model\Storage;
use SprykerFeature\Zed\Storage\StorageDependencyProvider;

/**
 * @method StorageBusiness getFactory()
 */
class StorageDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Storage
     */
    public function createStorage()
    {
        return $this->getFactory()->createModelStorage(
            $this->createStorageClient()
        );
    }

    /**
     * @return StorageClient
     */
    private function createStorageClient()
    {
        return $this->getProvidedDependency(StorageDependencyProvider::CLIENT_STORAGE);
    }

}
