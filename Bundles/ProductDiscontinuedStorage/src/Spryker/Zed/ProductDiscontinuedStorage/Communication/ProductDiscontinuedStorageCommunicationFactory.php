<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinueStorageMapper\ProductDiscontinuedStorageMapper;
use Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinueStorageMapper\ProductDiscontinuedStorageMapperInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface getRepository()
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

    /**
     * @return \Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinueStorageMapper\ProductDiscontinuedStorageMapperInterface
     */
    public function createProductDiscontinuedStorageMapper(): ProductDiscontinuedStorageMapperInterface
    {
        return new ProductDiscontinuedStorageMapper();
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::FACADE_PRODUCT_DISCONTINUED);
    }
}
