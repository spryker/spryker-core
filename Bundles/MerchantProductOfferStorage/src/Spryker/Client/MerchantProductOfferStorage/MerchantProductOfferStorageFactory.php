<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReader;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;

class MerchantProductOfferStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    public function createProductOfferStorageReader(): ProductOfferStorageReaderInterface
    {
        return new ProductOfferStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface
     */
    public function getStorageClient(): MerchantProductOfferStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): MerchantProductOfferStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
