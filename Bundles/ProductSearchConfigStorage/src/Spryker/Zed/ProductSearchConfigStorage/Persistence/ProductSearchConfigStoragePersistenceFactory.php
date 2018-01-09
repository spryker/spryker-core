<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Persistence;

use Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageConfig getConfig()
 * @method \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface getQueryContainer()
 */
class ProductSearchConfigStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductSearchConfigStorage\Dependency\QueryContainer\ProductSearchConfigStorageToProductSearchQueryContainerInterface
     */
    public function getProductSearchQuery()
    {
        return $this->getProvidedDependency(ProductSearchConfigStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_SEARCH);
    }

    /**
     * @return \Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorageQuery
     */
    public function createSpyProductSearchConfigStorageQuery()
    {
        return SpyProductSearchConfigStorageQuery::create();
    }
}
