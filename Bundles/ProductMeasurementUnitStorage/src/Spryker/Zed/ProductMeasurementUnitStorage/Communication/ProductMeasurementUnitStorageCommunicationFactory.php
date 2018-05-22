<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig getConfig()
 */
class ProductMeasurementUnitStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductMeasurementUnitStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
