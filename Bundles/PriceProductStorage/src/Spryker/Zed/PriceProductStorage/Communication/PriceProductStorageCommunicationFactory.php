<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Service\PriceProductStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\PriceProductStorage\PriceProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class PriceProductStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return PriceProductStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return PriceProductStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return PriceProductStorageToPriceProductFacadeInterface
     */
    public function getPriceProductFacade()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::STORE);
    }

}
