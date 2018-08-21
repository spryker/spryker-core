<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Communication;

use Spryker\Zed\AvailabilityResourceAliasStorage\AvailabilityResourceAliasStorageDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class AvailabilityResourceAliasStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityResourceAliasStorage\Dependency\Facade\AvailabilityResourceAliasStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(AvailabilityResourceAliasStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
