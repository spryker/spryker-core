<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductImageStorage\ProductImageStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductImageStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
