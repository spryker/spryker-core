<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Mapper\ProductOfferServiceMapper;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Mapper\ProductOfferServiceMapperInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferServiceStorageReader;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferServiceStorageReaderInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOffer\ProductOfferServiceStorageByProductOfferEventsWriter;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOffer\ProductOfferServiceStorageByProductOfferEventsWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferService\ProductOfferServiceStorageByProductOfferServiceEventsWriter;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferService\ProductOfferServiceStorageByProductOfferServiceEventsWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriter;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ServicePoint\ProductOfferServiceStorageByServicePointEventsWriter;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ServicePoint\ProductOfferServiceStorageByServicePointEventsWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageRepositoryInterface getRepository()
 */
class ProductOfferServicePointStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOffer\ProductOfferServiceStorageByProductOfferEventsWriterInterface
     */
    public function createProductOfferServiceStorageByProductOfferEventsWriter(): ProductOfferServiceStorageByProductOfferEventsWriterInterface
    {
        return new ProductOfferServiceStorageByProductOfferEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->createProductOfferServiceStorageWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferService\ProductOfferServiceStorageByProductOfferServiceEventsWriterInterface
     */
    public function createProductOfferServiceStorageByProductOfferServiceEventsWriter(): ProductOfferServiceStorageByProductOfferServiceEventsWriterInterface
    {
        return new ProductOfferServiceStorageByProductOfferServiceEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->getProductOfferServicePointFacade(),
            $this->createProductOfferServiceStorageWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ServicePoint\ProductOfferServiceStorageByServicePointEventsWriterInterface
     */
    public function createProductOfferServiceStorageByServicePointEventsWriter(): ProductOfferServiceStorageByServicePointEventsWriterInterface
    {
        return new ProductOfferServiceStorageByServicePointEventsWriter(
            $this->getEventBehaviorFacade(),
            $this->getServicePointFacade(),
            $this->getProductOfferServicePointFacade(),
            $this->getConfig(),
            $this->createProductOfferServiceStorageWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface
     */
    public function createProductOfferServiceStorageWriter(): ProductOfferServiceStorageWriterInterface
    {
        return new ProductOfferServiceStorageWriter(
            $this->getProductOfferServicePointFacade(),
            $this->getStoreFacade(),
            $this->getEntityManager(),
            $this->createProductOfferServiceMapper(),
            $this->createProductOfferReader(),
            $this->getProductOfferServiceStorageFilterPlugins(),
            $this->getProductOfferServiceCollectionStorageFilterPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Business\Mapper\ProductOfferServiceMapperInterface
     */
    public function createProductOfferServiceMapper(): ProductOfferServiceMapperInterface
    {
        return new ProductOfferServiceMapper();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferServiceStorageReaderInterface
     */
    public function createProductOfferServiceStorageReader(): ProductOfferServiceStorageReaderInterface
    {
        return new ProductOfferServiceStorageReader(
            $this->getRepository(),
            $this->getProductOfferServicePointFacade(),
            $this->createProductOfferReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader($this->getProductOfferFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
     */
    public function getProductOfferServicePointFacade(): ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_PRODUCT_OFFER_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferServicePointStorageToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ProductOfferServicePointStorageToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductOfferServicePointStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferServicePointStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductOfferServicePointStorage\Business\ProductOfferServicePointStorageBusinessFactory::getProductOfferServiceCollectionStorageFilterPlugins()} instead.
     *
     * @return list<\Spryker\Zed\ProductOfferServicePointStorageExtension\Dependeency\Plugin\ProductOfferServiceStorageFilterPluginInterface>
     */
    public function getProductOfferServiceStorageFilterPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_STORAGE_FILTER);
    }

    /**
     * @return list<\Spryker\Zed\ProductOfferServicePointStorageExtension\Dependency\Plugin\ProductOfferServiceCollectionStorageFilterPluginInterface>
     */
    public function getProductOfferServiceCollectionStorageFilterPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferServicePointStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_COLLECTION_STORAGE_FILTER);
    }
}
