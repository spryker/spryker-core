<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductPackagingUnitStorage\PriceProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\PriceProductStorageConfig getConfig()
 */
class PriceProductStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\PriceProductStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
