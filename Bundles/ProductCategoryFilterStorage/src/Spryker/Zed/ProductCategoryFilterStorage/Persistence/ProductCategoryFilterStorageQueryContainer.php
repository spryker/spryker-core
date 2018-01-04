<?php

namespace Spryker\Zed\ProductCategoryFilterStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStoragePersistenceFactory getFactory()
 */
class ProductCategoryFilterStorageQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterStorageQueryContainerInterface
{

    /**
     * @param array $idCategories
     *
     * @return $this|\Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery
     */
    public function queryProductCategoryFilterStorageByFkCategories(array $idCategories)
    {
        return $this->getFactory()
            ->createSpyProductCategoryFilterStorageQuery()
            ->filterByFkCategory_In($idCategories);
    }

    /**
     * @param array $categoryIds
     *
     * @return $this|\Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByIdCategories(array $categoryIds)
    {
        return $this->getFactory()
            ->getProductCategoryFilterQuery()
            ->queryProductCategoryFilter()
            ->filterByFkCategory_In($categoryIds);
    }
}
