<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductGroupStorage\Dependency\Facade\ProductGroupStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductGroupStorage\Dependency\Service\ProductGroupStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductGroupStorage\ProductGroupStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductGroupStorage\ProductGroupStorageConfig getConfig()
 */
class ProductGroupStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductGroupStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductGroupStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::STORE);
    }

}
