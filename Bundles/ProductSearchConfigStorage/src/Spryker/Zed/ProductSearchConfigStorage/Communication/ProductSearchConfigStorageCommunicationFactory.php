<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageConfig getConfig()
 */
class ProductSearchConfigStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
