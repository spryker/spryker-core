<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductMerchantRelationshipStorage\MerchantRelationshipFinder\MerchantRelationshipFinder;
use Spryker\Client\PriceProductMerchantRelationshipStorage\MerchantRelationshipFinder\MerchantRelationshipFinderInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToCustomerClientInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStorageClientInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStoreClientInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductMerchantRelationshipToSynchornizationServiceInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipAbstractReader;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipAbstractReaderInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipConcreteReader;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipConcreteReaderInterface;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipKeyGenerator;
use Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipKeyGeneratorInterface;

class PriceProductMerchantRelationshipStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipAbstractReaderInterface
     */
    public function createPriceProductMerchantRelationshipAbstractReader(): PriceProductMerchantRelationshipAbstractReaderInterface
    {
        return new PriceProductMerchantRelationshipAbstractReader(
            $this->getStorageClient(),
            $this->createPriceProductMerchantRelationshipKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipConcreteReaderInterface
     */
    public function createPriceProductMerchantRelationshipConcreteReader(): PriceProductMerchantRelationshipConcreteReaderInterface
    {
        return new PriceProductMerchantRelationshipConcreteReader(
            $this->getStorageClient(),
            $this->createPriceProductMerchantRelationshipKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\MerchantRelationshipFinder\MerchantRelationshipFinderInterface
     */
    public function createMerchantRelationshipFinder(): MerchantRelationshipFinderInterface
    {
        return new MerchantRelationshipFinder($this->getCustomerClient());
    }

    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\Storage\PriceProductMerchantRelationshipKeyGeneratorInterface
     */
    public function createPriceProductMerchantRelationshipKeyGenerator(): PriceProductMerchantRelationshipKeyGeneratorInterface
    {
        return new PriceProductMerchantRelationshipKeyGenerator(
            $this->getSynchronizationService(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToCustomerClientInterface
     */
    public function getCustomerClient(): PriceProductMerchantRelationshipStorageToCustomerClientInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStorageClientInterface
     */
    public function getStorageClient(): PriceProductMerchantRelationshipStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToStoreClientInterface
     */
    public function getStoreClient(): PriceProductMerchantRelationshipStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Service\PriceProductMerchantRelationshipToSynchornizationServiceInterface
     */
    public function getSynchronizationService(): PriceProductMerchantRelationshipToSynchornizationServiceInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
