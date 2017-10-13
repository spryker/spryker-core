<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeArchiveQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapArchiveQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 */
class ProductSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function createProductQuery()
    {
        return SpyProductQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function createProductSearchQuery()
    {
        return SpyProductSearchQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function createProductSearchAttributeMapQuery()
    {
        return SpyProductSearchAttributeMapQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapArchiveQuery
     */
    public function createProductSearchAttributeMapArchiveQuery()
    {
        return SpyProductSearchAttributeMapArchiveQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function createProductAttributeKeyQuery()
    {
        return SpyProductAttributeKeyQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function createProductSearchAttributeQuery()
    {
        return SpyProductSearchAttributeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeArchiveQuery
     */
    public function createProductSearchAttributeArchiveQuery()
    {
        return SpyProductSearchAttributeArchiveQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery()
    {
        return SpyProductAbstractQuery::create();
    }
}
