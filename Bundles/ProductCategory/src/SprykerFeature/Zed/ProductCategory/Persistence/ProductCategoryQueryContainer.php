<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;

/**
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 */
class ProductCategoryQueryContainer extends AbstractQueryContainer implements ProductCategoryQueryContainerInterface
{

    const COL_CATEGORY_NAME = 'category_name';

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
        return $this->getDependencyContainer()->createProductCategoryQuery();
    }

    /**
     * @param int $idAbstractProduct
     * @param int $idCategory
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idAbstractProduct, $idCategory)
    {
        $query = $this->queryProductCategoryMappings();
        $query
            ->filterByFkAbstractProduct($idAbstractProduct)
            ->filterByFkCategory($idCategory)
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
            ->endUse()
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
     * @param SpyAbstractProduct $abstractProduct
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingByProduct(SpyAbstractProduct $abstractProduct)
    {
        $query = $this->queryProductCategoryMappings();
        $query->filterByFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->useSpyCategoryNodeQuery()
                ->useCategoryQuery()
                    ->useAttributeQuery()
                    ->endUse()
                    ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::COL_CATEGORY_NAME)
                ->endUse()
            ->endUse()
        ;

        return $query;
    }

    /**
     * @param $idCategory
     * @param LocaleTransfer $locale
     * 
     * @return SpyProductCategoryQuery
     */
    public function queryProductsByCategoryId($idCategory, LocaleTransfer $locale)
    {
        return $this->queryProductCategoryMappings()
            ->innerJoinSpyAbstractProduct()
            //->withColumn(SpyAbstractProductTableMap::COL_SKU, 'sku')
            ->addJoin(
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_ABSTRACT_PRODUCT,
                Criteria::INNER_JOIN
            )
            ->addJoin(
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN
            )
            ->addAnd(
                SpyLocaleTableMap::COL_ID_LOCALE,
                $locale->getIdLocale(),
                Criteria::EQUAL
            )
            ->addAnd(
                SpyLocaleTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->withColumn(
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
                'name'
            )
            ->withColumn(
                SpyAbstractProductTableMap::COL_ATTRIBUTES,
                'abstract_attributes'
            )
            ->withColumn(
                SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
                'abstract_localized_attributes'
            )
            ->filterByFkCategory($idCategory)
        ;
    }

}
