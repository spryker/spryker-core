<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceStorage\Dependency\Facade\PriceStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceStorage\Dependency\Service\PriceStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\PriceStorage\PriceStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceStorage\Persistence\PriceStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\PriceStorage\PriceStorageConfig getConfig()
 */
class PriceStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return PriceStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(PriceStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return PriceStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(PriceStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(PriceStorageDependencyProvider::STORE);
    }

}
