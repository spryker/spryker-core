<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;

/**
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 */
class ProductCategoryQueryContainer extends AbstractQueryContainer implements ProductCategoryQueryContainerInterface
{

    /**
     * @param ModelCriteria $query
     * @param LocaleTransfer $locale
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return ModelCriteria
     */
    public function expandProductCategoryPathQuery(
        ModelCriteria $query,
        LocaleTransfer $locale,
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
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, LocaleTransfer $locale)
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

    /**
     * @param int $idCategoryNode
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIdCategory($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        $query = $this->queryProductCategoryMappings();

        $query->innerJoinSpyAbstractProduct()
            ->useSpyCategoryNodeQuery()
                ->filterByFkCategory($idCategoryNode)
            ->endUse()
            ->useSpyCategoryNodeQuery()
                ->useCategoryQuery()
                    ->useAttributeQuery()
                        ->useLocaleQuery()
                            ->filterByLocaleName($localeTransfer->getLocaleName())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse();

        return $query;
    }
}
