<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientInterface;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapper;
use Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefault\ProductConcreteDefaultProductOffer;
use Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefault\ProductConcreteDefaultProductOfferInterface;
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
            $this->getSynchronizationService(),
            $this->createMerchantProductOfferMapper()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\ProductConcreteDefault\ProductConcreteDefaultProductOfferInterface
     */
    public function createProductConcreteDefaultProductOffer(): ProductConcreteDefaultProductOfferInterface
    {
        return new ProductConcreteDefaultProductOffer($this->createProductOfferStorageReader());
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Mapper\MerchantProductOfferMapperInterface
     */
    public function createMerchantProductOfferMapper(): MerchantProductOfferMapperInterface
    {
        return new MerchantProductOfferMapper();
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
