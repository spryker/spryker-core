<?php


namespace Spryker\Zed\ProductCategoryFilter\Persistence;


interface ProductCategoryFilterQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryFilterByCategoryId($idCategory);
}