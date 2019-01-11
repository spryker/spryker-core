<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageMapper\ProductPackagingUnitStorageMapper;
use Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageMapper\ProductPackagingUnitStorageMapperInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface getFacade()
 */
class ProductPackagingUnitStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductPackagingUnitStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Communication\ProductPackagingUnitStorageMapper\ProductPackagingUnitStorageMapperInterface
     */
    public function createProductAbstractPackagingStorageMapper(): ProductPackagingUnitStorageMapperInterface
    {
        return new ProductPackagingUnitStorageMapper();
    }
}
