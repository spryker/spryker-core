<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface;
use Spryker\Zed\ProductOptionStorage\Dependency\Service\ProductOptionStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductOptionStorage\ProductOptionStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductOptionStorage\ProductOptionStorageConfig getConfig()
 */
class ProductOptionStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductOptionStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductOptionStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::STORE);
    }

    /**
     * @return ProductOptionStorageToProductOptionFacadeInterface
     */
    public function getProductOptionFacade()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::FACADE_PRODUCT_OPTION);
    }

}
