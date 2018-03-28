<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductQuantityStorage\Dependency\Facade\ProductQuantityStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageConfig getConfig()
 */
class ProductQuantityStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductQuantityStorage\Dependency\Facade\ProductQuantityStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductQuantityStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
