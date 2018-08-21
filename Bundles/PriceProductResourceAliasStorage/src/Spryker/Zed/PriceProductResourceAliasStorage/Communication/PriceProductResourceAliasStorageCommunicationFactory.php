<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductResourceAliasStorage\Dependency\Facade\PriceProductResourceAliasStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceProductResourceAliasStorage\PriceProductResourceAliasStorageDependencyProvider;

class PriceProductResourceAliasStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PriceProductResourceAliasStorage\Dependency\Facade\PriceProductResourceAliasStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): PriceProductResourceAliasStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
