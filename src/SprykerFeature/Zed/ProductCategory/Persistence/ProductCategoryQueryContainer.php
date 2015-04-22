<?php

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 */
class ProductCategoryQueryContainer extends AbstractQueryContainer implements ProductCategoryQueryContainerInterface
{
    /**
     * @param ModelCriteria $query
     * @param string $locale
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return ModelCriteria
     */
    public function expandProductCategoryPathQuery(
        ModelCriteria $query,
        $locale,
        $excludeDirectParent = true,
        $excludeRoot = true
    ) {
        return $this->getDependencyContainer()
            ->createProductCategoryPathQueryExpander($locale)
            ->expandQuery($query, $excludeDirectParent, $excludeRoot);
    }

    /**
     * @param int $idProduct
     * @param int $idCategoryNode
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idProduct, $idCategoryNode)
    {
        $query = SpyProductCategoryQuery::create();
        $query
            ->filterByFkProduct($idProduct)
            ->filterByFkCategoryNode($idCategoryNode)
        ;

        return $query;
    }
}
