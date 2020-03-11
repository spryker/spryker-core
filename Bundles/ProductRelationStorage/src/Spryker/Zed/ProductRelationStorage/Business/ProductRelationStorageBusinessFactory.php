<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductRelationStorage\Business\Storage\ProductRelationStorageWriter;
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
     * @return \Spryker\Zed\ProductRelationStorage\Business\Storage\ProductRelationStorageWriterInterface
     */
    public function createProductRelationStorageWriter()
    {
        return new ProductRelationStorageWriter(
            $this->getRepository(),
            $this->getProductRelationFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface
     */
    public function getProductRelationFacade(): ProductRelationStorageToProductRelationFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::FACADE_PRODUCT_RELATION);
    }
}
