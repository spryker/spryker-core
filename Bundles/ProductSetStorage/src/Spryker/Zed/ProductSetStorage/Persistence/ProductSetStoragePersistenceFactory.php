<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Persistence;

use Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductSetStorage\ProductSetStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainer getQueryContainer()
 */
class ProductSetStoragePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorageQuery
     */
    public function createSpyProductSetStorageQuery()
    {
        return SpyProductSetStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductSetStorage\Dependency\QueryContainer\ProductSetStorageToProductSetQueryContainerInterface
     */
    public function getProductSetQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ProductSetStorage\Dependency\QueryContainer\ProductSetStorageToProductImageQueryContainerInterface
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

}
