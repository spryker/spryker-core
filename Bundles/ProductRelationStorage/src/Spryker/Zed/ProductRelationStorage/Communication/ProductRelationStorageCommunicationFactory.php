<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductRelationStorage\ProductRelationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface getFacade()
 */
class ProductRelationStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
