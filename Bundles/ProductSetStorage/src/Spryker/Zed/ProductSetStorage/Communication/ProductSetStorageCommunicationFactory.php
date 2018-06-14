<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSetStorage\ProductSetStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 */
class ProductSetStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
