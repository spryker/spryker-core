<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication;

use Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\GlossaryStorage\GlossaryStorageDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainer getQueryContainer()
 */
class GlossaryStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return GlossaryStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return GlossaryStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
