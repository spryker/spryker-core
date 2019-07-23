<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface;
use Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReader;
use Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReaderInterface;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReader;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface;
use Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReader;
use Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface;
use Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReader;
use Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface;
use Spryker\Client\ProductListStorage\ProductRestrictionFilter\ProductAbstractProductRestrictionFilter;
use Spryker\Client\ProductListStorage\ProductRestrictionFilter\ProductConcreteProductRestrictionFilter;
use Spryker\Client\ProductListStorage\ProductRestrictionFilter\ProductRestrictionFilterInterface;
use Spryker\Client\ProductListStorage\ProductViewVariantRestrictionExpander\ProductViewVariantRestrictionExpander;
use Spryker\Client\ProductListStorage\ProductViewVariantRestrictionExpander\ProductViewVariantRestrictionExpanderInterface;

class ProductListStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface
     */
    public function createProductListProductAbstractStorageReader(): ProductListProductAbstractStorageReaderInterface
    {
        return new ProductListProductAbstractStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface
     */
    public function createProductListProductConcreteStorageReader(): ProductListProductConcreteStorageReaderInterface
    {
        return new ProductListProductConcreteStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductListStorage\ProductAbstractRestriction\ProductAbstractRestrictionReaderInterface
     */
    public function createProductAbstractRestrictionReader(): ProductAbstractRestrictionReaderInterface
    {
        return new ProductAbstractRestrictionReader(
            $this->getCustomerClient(),
            $this->createProductListProductAbstractStorageReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface
     */
    public function createProductConcreteRestrictionReader(): ProductConcreteRestrictionReaderInterface
    {
        return new ProductConcreteRestrictionReader(
            $this->getCustomerClient(),
            $this->createProductListProductConcreteStorageReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductListStorage\ProductRestrictionFilter\ProductRestrictionFilterInterface
     */
    public function createProductAbstractProductRestrictionFilter(): ProductRestrictionFilterInterface
    {
        return new ProductAbstractProductRestrictionFilter($this->getCustomerClient(), $this->createProductListProductAbstractStorageReader());
    }

    /**
     * @return \Spryker\Client\ProductListStorage\ProductRestrictionFilter\ProductRestrictionFilterInterface
     */
    public function createProductConcreteProductRestrictionFilter(): ProductRestrictionFilterInterface
    {
        return new ProductConcreteProductRestrictionFilter($this->getCustomerClient(), $this->createProductListProductConcreteStorageReader());
    }

    /**
     * @return \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductListStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface
     */
    public function getCustomerClient(): ProductListStorageToCustomerClientInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ProductListStorage\Dependency\Service\ProductListStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductListStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductListStorage\ProductViewVariantRestrictionExpander\ProductViewVariantRestrictionExpanderInterface
     */
    public function createProductViewVariantRestrictionExpander(): ProductViewVariantRestrictionExpanderInterface
    {
        return new ProductViewVariantRestrictionExpander($this->createProductConcreteRestrictionReader());
    }
}
