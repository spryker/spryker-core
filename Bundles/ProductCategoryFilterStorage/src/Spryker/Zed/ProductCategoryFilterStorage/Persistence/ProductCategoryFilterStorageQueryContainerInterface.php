<?php

namespace Spryker\Zed\ProductCategoryFilterStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductCategoryFilterStorageQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param array $idCategories
     *
     * @return $this|\Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery
     */
    public function queryProductCategoryFilterStorageByFkCategories(array $idCategories);


    /**
     * @param array $categoryIds
     *
     * @return $this|\Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByIdCategories(array $categoryIds);
}
