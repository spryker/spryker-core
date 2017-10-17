<?php

namespace Spryker\Zed\ProductCategoryFilter\Persistence;

use Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class ProductCategoryFilterPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function createProductGroupQuery()
    {
        return SpyProductCategoryFilterQuery::create();
    }
}
