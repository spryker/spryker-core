<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToEventBehaviourFacadeInterface;
use Spryker\Zed\ProductListStorage\ProductListStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListStorage\ProductListStorageConfig getConfig()
 */
class ProductListStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToEventBehaviourFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductListStorageToEventBehaviourFacadeInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
