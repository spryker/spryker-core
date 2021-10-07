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
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Writer\PriceProductMerchantRelationshipStorageWriter;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Writer\PriceProductMerchantRelationshipStorageWriterInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface;
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
            $this->createPriceGrouper(),
            $this->getPriceProductMerchantRelationshipStorageFilterPlugins()
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
            $this->createPriceGrouper(),
            $this->getPriceProductMerchantRelationshipStorageFilterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Writer\PriceProductMerchantRelationshipStorageWriterInterface
     */
    public function createPriceProductMerchantRelationshipStorageWriter(): PriceProductMerchantRelationshipStorageWriterInterface
    {
        return new PriceProductMerchantRelationshipStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getMerchantRelationshipFacade(),
            $this->createPriceProductAbstractStorageWriter(),
            $this->createPriceProductConcreteStorageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface
     */
    public function createPriceGrouper(): PriceGrouperInterface
    {
        return new PriceGrouper();
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): PriceProductMerchantRelationshipStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return array<\Spryker\Zed\PriceProductMerchantRelationshipStorageExtension\Dependency\Plugin\PriceProductMerchantRelationshipStorageFilterPluginInterface>
     */
    public function getPriceProductMerchantRelationshipStorageFilterPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::PLUGINS_PRICE_PRODUCT_MERCHANT_RELATIONSHIP_STORAGE_FILTER);
    }
}
