<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToStorageClientInterface;
use Spryker\Client\MerchantProductStorage\Dependency\Service\MerchantProductStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapper;
use Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReader;
use Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReaderInterface;

class MerchantProductStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductStorage\Reader\MerchantProductStorageReaderInterface
     */
    public function createMerchantProductStorageReader(): MerchantProductStorageReaderInterface
    {
        return new MerchantProductStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createMerchantProductStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProductStorage\Mapper\MerchantProductStorageMapper
     */
    public function createMerchantProductStorageMapper(): MerchantProductStorageMapper
    {
        return new MerchantProductStorageMapper();
    }

    /**
     * @return \Spryker\Client\MerchantProductStorage\Dependency\Client\MerchantProductStorageToStorageClientInterface
     */
    public function getStorageClient(): MerchantProductStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\MerchantProductStorage\Dependency\Service\MerchantProductStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): MerchantProductStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(MerchantProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
