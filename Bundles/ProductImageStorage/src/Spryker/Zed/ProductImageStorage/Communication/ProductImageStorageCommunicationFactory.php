<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductImageStorage\Dependency\Service\ProductImageStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductImageStorage\ProductImageStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductImageStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductImageStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductImageStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageBridge
     */
    public function getProductImageFacade()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

}
