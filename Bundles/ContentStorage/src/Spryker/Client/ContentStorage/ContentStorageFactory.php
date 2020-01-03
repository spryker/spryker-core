<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Spryker\Client\ContentStorage\ContentStorage\ContentStorageReader;
use Spryker\Client\ContentStorage\ContentStorage\ContentStorageReaderInterface;
use Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface;
use Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ContentStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentStorage\ContentStorage\ContentStorageReaderInterface
     */
    public function createContentStorage(): ContentStorageReaderInterface
    {
        return new ContentStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientInterface
     */
    public function getStorageClient(): ContentStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ContentStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
