<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductResourceAliasStorage\ProductResourceAliasStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductResourceAliasStorage\ProductResourceAliasStorageConfig getConfig()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Business\ProductResourceAliasStorageFacadeInterface getFacade()
 */
class ProductResourceAliasStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductResourceAliasStorage\Dependency\Facade\ProductResourceAliasStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductResourceAliasStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
