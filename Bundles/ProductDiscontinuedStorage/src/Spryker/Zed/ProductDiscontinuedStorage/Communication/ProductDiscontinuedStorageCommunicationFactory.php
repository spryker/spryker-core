<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig getConfig()
 */
class ProductDiscontinuedStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductDiscontinuedStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
