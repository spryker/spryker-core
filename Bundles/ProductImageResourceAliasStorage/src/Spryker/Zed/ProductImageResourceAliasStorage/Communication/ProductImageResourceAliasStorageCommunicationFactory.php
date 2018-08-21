<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductImageResourceAliasStorage\Dependency\Facade\ProductImageResourceAliasStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductImageResourceAliasStorage\ProductImageResourceAliasStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\ProductImageResourceAliasStorageConfig getConfig()
 */
class ProductImageResourceAliasStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductImageResourceAliasStorage\Dependency\Facade\ProductImageResourceAliasStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductImageResourceAliasStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductImageResourceAliasStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
