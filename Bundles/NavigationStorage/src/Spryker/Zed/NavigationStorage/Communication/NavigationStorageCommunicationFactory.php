<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\NavigationStorage\NavigationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\NavigationStorage\NavigationStorageConfig getConfig()
 */
class NavigationStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
