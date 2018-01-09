<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageConfig getConfig()
 */
class ProductSearchConfigStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Dependency\Service\ProductSearchConfigStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\ProductSearchConfig
     */
    public function getProductSearchConfig()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::CONFIG_PRODUCT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToProductSearchToProductSearchInterface
     */
    public function getProductSearchFacade()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::FACADE_PRODUCT_SEARCH);
    }
}
