<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouper;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductAbstractStorageWriter;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductAbstractStorageWriterInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductConcreteStorageWriter;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductConcreteStorageWriterInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToStoreFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig getConfig()
 */
class PriceProductMerchantRelationshipStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductAbstractStorageWriterInterface
     */
    public function createPriceProductAbstractStorageWriter(): PriceProductAbstractStorageWriterInterface
    {
        return new PriceProductAbstractStorageWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createPriceGrouper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductConcreteStorageWriterInterface
     */
    public function createPriceProductConcreteStorageWriter(): PriceProductConcreteStorageWriterInterface
    {
        return new PriceProductConcreteStorageWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createPriceGrouper()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface
     */
    public function createPriceGrouper(): PriceGrouperInterface
    {
        return new PriceGrouper($this->getPriceProductFacade(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): PriceProductMerchantRelationshipStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::FACADE_STORE);
    }
}
