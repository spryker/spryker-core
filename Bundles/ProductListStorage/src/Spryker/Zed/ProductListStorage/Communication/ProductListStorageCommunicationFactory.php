<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductListStorage\ProductListStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListStorage\ProductListStorageConfig getConfig()
 * @method \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductListStorage\Business\ProductListStorageFacadeInterface getFacade()
 */
class ProductListStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductListStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
