<?php

namespace Spryker\Zed\ProductCategoryFilter\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterPersistenceFactory getFactory()
 */
class ProductCategoryFilterQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterQueryContainerInterface
{

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryFilterByCategoryId($idCategory)
    {
        return $this->getFactory()->createProductGroupQuery()->filterByFkCategory($idCategory);
    }
}
