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
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\Map\SpyProductCategoryTableMap;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategory;
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
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingsByCategoryId($idCategory)
    {
        return $this->getDependencyContainer()
            ->createProductCategoryQuery()
            ->filterByFkCategory($idCategory)
        ;
    }

    /**
     * @param int $idCategory
     * @param int $idAbstractProduct
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idCategory, $idAbstractProduct)
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
            ->useSpyCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($locale->getIdLocale())
                    ->filterByName($categoryName)
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
            ->useSpyCategoryQuery()
                ->useAttributeQuery()
                    ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::COL_CATEGORY_NAME)
                ->endUse()
            ->endUse()
        ;

        return $query;
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductsByCategoryId($idCategory, LocaleTransfer $locale)
    {
        return $this->queryProductCategoryMappings()
            ->innerJoinSpyAbstractProduct()
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
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                'id_abstract_product'
            )
            ->withColumn(
                SpyAbstractProductTableMap::COL_ATTRIBUTES,
                'abstract_attributes'
            )
            ->withColumn(
                SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
                'abstract_localized_attributes'
            )
            ->withColumn(
                SpyAbstractProductTableMap::COL_SKU,
                'sku'
            )
            ->withColumn(
                SpyProductCategoryTableMap::COL_PRODUCT_ORDER,
                'product_order'
            )
            ->withColumn(
                SpyProductCategoryTableMap::COL_ID_PRODUCT_CATEGORY,
                'id_product_category'
            )
            //TODO remove when properly implementing product categories
                //https://kartenmacherei.atlassian.net/browse/KSP-877
/*            ->withColumn(
                SpyProductCategoryTableMap::COL_FK_PRECONFIG_PRODUCT,
                'preconfig_product'
            )*/
            ->filterByFkCategory($idCategory)
            ->orderByFkAbstractProduct()
        ;
    }

    /**
     * @param $term
     * @param LocaleTransfer $locale
     * @param int $idExcludedCategory null
     *
     * @return SpyAbstractProductQuery
     */
    public function queryAbstractProductsBySearchTerm($term, LocaleTransfer $locale, $idExcludedCategory = null)
    {
        $idExcludedCategory = (int) $idExcludedCategory;
        $query = SpyAbstractProductQuery::create();

        $query->addJoin(
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
        ->withColumn(
            SpyAbstractProductTableMap::COL_SKU,
            'sku'
        );

        $query->groupByAttributes();
        $query->groupByIdAbstractProduct();

        if ('' !== trim($term)) {
            $term = '%' . mb_strtoupper($term) . '%';

            $query->where('UPPER(' . SpyAbstractProductTableMap::COL_SKU . ') LIKE ?', $term, \PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyLocalizedAbstractProductAttributesTableMap::COL_NAME . ') LIKE ?', $term, \PDO::PARAM_STR)
            ;
        }

        if ($idExcludedCategory > 0) {
            $query
                ->addJoin(
                    SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                    SpyProductCategoryTableMap::COL_FK_ABSTRACT_PRODUCT,
                    Criteria::INNER_JOIN
                )
                ->_and()
                ->where(SpyProductCategoryTableMap::COL_FK_CATEGORY . ' <> ?', $idExcludedCategory, \PDO::PARAM_INT);
        }

        return $query;
    }

    /**
     * @param int $idCategory
     * @param int $idAbstractProduct
     *
     * @return SpyProductQuery
     */
    public function queryProductCategoryPreconfig($idCategory, $idAbstractProduct)
    {
        return SpyProductQuery::create()
            ->filterByFkAbstractProduct($idAbstractProduct)
            ->addAnd(
                SpyProductTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
        ;
    }

}
