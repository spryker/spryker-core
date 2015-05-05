<?php

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;

/**
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 */
class ProductCategoryQueryContainer extends AbstractQueryContainer implements ProductCategoryQueryContainerInterface
{
    /**
     * @param ModelCriteria $query
     * @param LocaleDto $locale
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return ModelCriteria
     */
    public function expandProductCategoryPathQuery(
        ModelCriteria $query,
        LocaleDto $locale,
        $excludeDirectParent = true,
        $excludeRoot = true
    ) {
        return $this->getDependencyContainer()
            ->createProductCategoryPathQueryExpander($locale)
            ->expandQuery($query, $excludeDirectParent, $excludeRoot);
    }

    /**
     * @return SpyProductCategoryQuery
     */
    protected function queryProductCategoryMappings()
    {
        $query = $this->getDependencyContainer()->createProductCategoryQuery();

        return $query;
    }

    /**
     * @param int $idAbstractProduct
     * @param int $idCategoryNode
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idAbstractProduct, $idCategoryNode)
    {
        $query = $this->queryProductCategoryMappings();
        $query
            ->filterByFkAbstractProduct($idAbstractProduct)
            ->filterByFkCategoryNode($idCategoryNode)
        ;

        return $query;
    }

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleDto $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, LocaleDto $locale)
    {
        $query = $this->queryProductCategoryMappings();
        $query
            ->useSpyAbstractProductQuery()
            ->filterBySku($sku)
            ->endUse()
            ->useSpyCategoryNodeQuery()
            ->useCategoryQuery()
            ->useAttributeQuery()
            ->filterByFkLocale($locale->getIdLocale())
            ->filterByName($categoryName)
            ->endUse()
            ->endUse()
            ->endUse()
        ;

        return $query;
    }
}
