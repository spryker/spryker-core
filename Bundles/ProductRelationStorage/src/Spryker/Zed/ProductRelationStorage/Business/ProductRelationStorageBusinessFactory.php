<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductRelationStorage\Business\Grouper\ProductRelationStorageGrouper;
use Spryker\Zed\ProductRelationStorage\Business\Grouper\ProductRelationStorageGrouperInterface;
use Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriter;
use Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriterInterface;
use Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface;
use Spryker\Zed\ProductRelationStorage\ProductRelationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface getEntityManager()
 */
class ProductRelationStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductRelationStorage\Business\Writer\ProductRelationStorageWriterInterface
     */
    public function createProductRelationStorageWriter(): ProductRelationStorageWriterInterface
    {
        return new ProductRelationStorageWriter(
            $this->getRepository(),
            $this->getProductRelationFacade(),
            $this->getEntityManager(),
            $this->getEventBehaviorFacade(),
            $this->createProductRelationStorageGrouper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Business\Grouper\ProductRelationStorageGrouperInterface
     */
    public function createProductRelationStorageGrouper(): ProductRelationStorageGrouperInterface
    {
        return new ProductRelationStorageGrouper();
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface
     */
    public function getProductRelationFacade(): ProductRelationStorageToProductRelationFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::FACADE_PRODUCT_RELATION);
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductRelationStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
