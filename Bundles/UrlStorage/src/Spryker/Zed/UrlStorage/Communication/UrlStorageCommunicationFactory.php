<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UrlStorage\Dependency\Facade\UrlStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\UrlStorage\UrlStorageDependencyProvider;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\UrlStorage\UrlStorageConfig getConfig()
 */
class UrlStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return UrlStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return UrlStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::STORE);
    }

}
