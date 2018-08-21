<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCategoryResourceAliasStorage\Dependency\Facade\ProductCategoryResourceAliasStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductCategoryResourceAliasStorage\ProductCategoryResourceAliasStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\ProductCategoryResourceAliasStorageConfig getConfig()
 */
class ProductCategoryResourceAliasStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryResourceAliasStorage\Dependency\Facade\ProductCategoryResourceAliasStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductCategoryResourceAliasStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductCategoryResourceAliasStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
