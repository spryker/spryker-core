<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfigurationStorage\Business\Deleter\ProductConfigurationStorageDeleter;
use Spryker\Zed\ProductConfigurationStorage\Business\Deleter\ProductConfigurationStorageDeleterInterface;
use Spryker\Zed\ProductConfigurationStorage\Business\Mapper\ProductConfigurationStorageMapper;
use Spryker\Zed\ProductConfigurationStorage\Business\Mapper\ProductConfigurationStorageMapperInterface;
use Spryker\Zed\ProductConfigurationStorage\Business\Writer\ProductConfigurationStorageWriter;
use Spryker\Zed\ProductConfigurationStorage\Business\Writer\ProductConfigurationStorageWriterInterface;
use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface;
use Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfigurationStorage\Persistence\ProductConfigurationStorageEntityManagerInterface getEntityManager()
 */
class ProductConfigurationStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfigurationStorage\Business\Writer\ProductConfigurationStorageWriterInterface
     */
    public function createProductConfigurationStorageWriter(): ProductConfigurationStorageWriterInterface
    {
        return new ProductConfigurationStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getProductConfigurationFacade(),
            $this->createProductConfigurationStorageMapper(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationStorage\Business\Deleter\ProductConfigurationStorageDeleterInterface
     */
    public function createProductConfigurationStorageDeleter(): ProductConfigurationStorageDeleterInterface
    {
        return new ProductConfigurationStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationStorage\Business\Mapper\ProductConfigurationStorageMapperInterface
     */
    public function createProductConfigurationStorageMapper(): ProductConfigurationStorageMapperInterface
    {
        return new ProductConfigurationStorageMapper();
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToProductConfigurationFacadeInterface
     */
    public function getProductConfigurationFacade(): ProductConfigurationStorageToProductConfigurationFacadeInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::FACADE_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationStorage\Dependency\Facade\ProductConfigurationStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductConfigurationStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
