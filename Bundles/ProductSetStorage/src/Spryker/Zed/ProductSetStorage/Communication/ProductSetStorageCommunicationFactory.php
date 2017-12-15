<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductSetStorage\Dependency\Service\ProductSetStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductSetStorage\ProductSetStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 */
class ProductSetStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductSetStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductSetStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::STORE);
    }

}
