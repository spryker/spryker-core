<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedAbstractProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributesMetadataQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributeTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\Map\SpyProductCategoryTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyUrlTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;

class ProductQueryContainer extends AbstractQueryContainer implements ProductQueryContainerInterface
{

    /**
     * @param string $skus
     * @param LocaleTransfer $locale
     *
     * @return SpyProductQuery
     */
    public function getProductWithAttributeQuery($skus, LocaleTransfer $locale)
    {
        $query = SpyProductQuery::create();
        $query->filterBySku($skus);
        $query->useSpyLocalizedProductAttributesQuery()
                    ->filterByFkLocale($locale->getIdLocale())
                ->endUse()

            ->addSelectColumn(SpyProductTableMap::COL_SKU)
            ->addSelectColumn(SpyLocalizedProductAttributesTableMap::COL_ATTRIBUTES)
            ->addSelectColumn(SpyLocalizedProductAttributesTableMap::COL_NAME)
            ->addAsColumn('sku', SpyProductTableMap::COL_SKU)
            ->addAsColumn('attributes', SpyLocalizedProductAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn('name', SpyLocalizedProductAttributesTableMap::COL_NAME);

        return $query;
    }

    /**
     * @param string $concreteSku
     * @param int $idLocale
     *
     * @return SpyProductQuery
     */
    public function queryProductWithAttributesAndAbstractProduct($concreteSku, $idLocale)
    {
        $query = SpyProductQuery::create();

        $query->filterBySku($concreteSku)
            ->useSpyLocalizedProductAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->useSpyAbstractProductQuery()
            ->endUse();

        return $query;
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return SpyTaxSetQuery
     */
    public function queryTaxSetForAbstractProduct($idAbstractProduct)
    {
        return SpyTaxSetQuery::create()
            ->useSpyAbstractProductQuery()
                ->filterByIdAbstractProduct($idAbstractProduct)
            ->endUse();
    }

    /**
     * @return SpyProductQuery
     */
    public function queryAbstractProducts()
    {
        return SpyAbstractProductQuery::create();
    }

    /**
     * @param string $sku
     *
     * @return SpyProductQuery
     */
    public function queryConcreteProductBySku($sku)
    {
        return SpyProductQuery::create()
            ->filterBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return SpyAbstractProductQuery
     */
    public function queryAbstractProductBySku($sku)
    {
        return SpyAbstractProductQuery::create()
            ->filterBySku($sku);
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return SpyAbstractProductQuery
     */
    public function querySkuFromAbstractProductById($idAbstractProduct)
    {
        return SpyAbstractProductQuery::create()
            ->filterByIdAbstractProduct($idAbstractProduct);
    }

    /**
     * @throws PropelException
     *
     * @return ModelCriteria
     */
    public function queryAbstractSkuForm()
    {
        return SpyAbstractProductQuery::create()
            ->select([
                SpyAbstractProductTableMap::COL_SKU => 'value',
                SpyAbstractProductTableMap::COL_SKU => 'label',
            ])
            ->withColumn(SpyAbstractProductTableMap::COL_SKU, 'value')
            ->withColumn(SpyAbstractProductTableMap::COL_SKU, 'label')
            ;
    }

    /**
     * @throws PropelException
     *
     * @return ModelCriteria
     */
    public function queryConcreteSkuForm()
    {
        return $query = SpyProductQuery::create()
            ->select([
                SpyProductTableMap::COL_SKU => 'value',
                SpyProductTableMap::COL_SKU => 'label',
            ])
            ->withColumn(SpyProductTableMap::COL_SKU, 'value')
            ->withColumn(SpyProductTableMap::COL_SKU, 'label');
    }

    /**
     * @param string $attributeName
     *
     * @return SpyProductAttributesMetadataQuery
     */
    public function queryAttributeByName($attributeName)
    {
        $query = SpyProductAttributesMetadataQuery::create();
        $query->filterByKey($attributeName);

        return $query;
    }

    /**
     * @param string $attributeType
     *
     * @return SpyProductAttributeTypeQuery
     */
    public function queryAttributeTypeByName($attributeType)
    {
        $query = SpyProductAttributeTypeQuery::create();
        $query->filterByName($attributeType);

        return $query;
    }

    /**
     * @param int $idAbstractProduct
     * @param int $fkCurrentLocale
     *
     * @return SpyLocalizedAbstractProductAttributesQuery
     */
    public function queryAbstractProductAttributeCollection($idAbstractProduct, $fkCurrentLocale)
    {
        $query = SpyLocalizedAbstractProductAttributesQuery::create();
        $query
            ->filterByFkAbstractProduct($idAbstractProduct)
            ->filterByFkLocale($fkCurrentLocale)
        ;

        return $query;
    }

    /**
     * @param int $idConcreteProduct
     * @param int $fkCurrentLocale
     *
     * @return SpyLocalizedProductAttributesQuery
     */
    public function queryConcreteProductAttributeCollection($idConcreteProduct, $fkCurrentLocale)
    {
        $query = SpyLocalizedProductAttributesQuery::create();
        $query
            ->filterByFkProduct($idConcreteProduct)
            ->filterByFkLocale($fkCurrentLocale)
        ;

        return $query;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinConcreteProducts(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoinObject(
                new Join(
                    SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                    SpyProductTableMap::COL_FK_ABSTRACT_PRODUCT,
                    Criteria::LEFT_JOIN
                ),
                'concreteProductJoin'
            );

        $expandableQuery->addJoinCondition(
            'concreteProductJoin',
            SpyProductTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL
        );

        $expandableQuery->withColumn(
            'GROUP_CONCAT(spy_product.sku)',
            'concrete_skus'
        );

        return $this;
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return $this
     */
    public function joinProductQueryWithLocalizedAttributes(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $expandableQuery
            ->addJoin(
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_ABSTRACT_PRODUCT,
                Criteria::INNER_JOIN
            );

        $expandableQuery
            ->addJoin(
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN
            );

        $expandableQuery->addAnd(
            SpyLocaleTableMap::COL_ID_LOCALE,
            $locale->getIdLocale(),
            Criteria::EQUAL
        );
        $expandableQuery->addAnd(
            SpyLocaleTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL
        );

        $expandableQuery
            ->addJoinObject(
                (new Join(
                    SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                    SpyUrlTableMap::COL_FK_RESOURCE_ABSTRACT_PRODUCT,
                    Criteria::LEFT_JOIN
                ))->setRightTableAlias('product_urls'),
                'productUrlsJoin'
            );

        $expandableQuery->addJoinCondition(
            'productUrlsJoin',
            'product_urls.fk_locale = ' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );

        $expandableQuery
            ->addJoinObject(
                new Join(
                    SpyProductTableMap::COL_ID_PRODUCT,
                    SpyLocalizedProductAttributesTableMap::COL_FK_PRODUCT,
                    Criteria::INNER_JOIN
                ),
                'productAttributesJoin'
            );

        $expandableQuery->addJoinCondition(
            'productAttributesJoin',
            SpyLocalizedProductAttributesTableMap::COL_FK_LOCALE . ' = ' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );

        $expandableQuery->withColumn(SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT, 'id_abstract_product');

        $expandableQuery->withColumn(
            SpyAbstractProductTableMap::COL_ATTRIBUTES,
            'abstract_attributes'
        );
        $expandableQuery->withColumn(
            SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
            'abstract_localized_attributes'
        );
        $expandableQuery->withColumn(
            "GROUP_CONCAT(spy_product.attributes SEPARATOR '$%')",
            'concrete_attributes'
        );
        $expandableQuery->withColumn(
            "GROUP_CONCAT(spy_product_localized_attributes.attributes SEPARATOR '$%')",
            'concrete_localized_attributes'
        );
        $expandableQuery->withColumn(
            'GROUP_CONCAT(product_urls.url)',
            'product_urls'
        );
        $expandableQuery->withColumn(
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
            'abstract_name'
        );
        $expandableQuery->withColumn(
            'GROUP_CONCAT(spy_product_localized_attributes.name)',
            'concrete_names'
        );

        return $this;
    }

    // @todo refactor queries from below

    public function queryConcreteProductByAbstractProduct(SpyAbstractProduct $abstractProduct)
    {
        return SpyProductQuery::create()
            ->filterByFkAbstractProduct($abstractProduct->getIdAbstractProduct());
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
        
        if ('' !== trim($term)) {
            $term = '%'.strtoupper($term).'%';

            $query->where('UPPER('.SpyAbstractProductTableMap::COL_SKU.') LIKE ?', $term, \PDO::PARAM_STR)
                ->_or()
                ->where('UPPER('.SpyLocalizedAbstractProductAttributesTableMap::COL_NAME.') LIKE ?', $term, \PDO::PARAM_STR)
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
                ->where(SpyProductCategoryTableMap::COL_FK_CATEGORY.' <> ?', $idExcludedCategory, \PDO::PARAM_INT);
        }

        return $query;
    }

    /**
     * @param $term
     * @param LocaleTransfer $locale
     * @param int $idExcludedCategory null
     *
     * @return SpyAbstractProductQuery
     */
    public function queryAbstractProductsBySearchTermFuckingLol($term, LocaleTransfer $locale, $idExcludedCategory = null)
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
                'SKU' //THIS HAS TO BE UPPERCASE WTF 
            );

        if ('' !== trim($term)) {
            $term = '%'.strtoupper($term).'%';

            $query->where('UPPER('.SpyAbstractProductTableMap::COL_SKU.') LIKE ?', $term, \PDO::PARAM_STR)
                ->_or()
                ->where('UPPER('.SpyLocalizedAbstractProductAttributesTableMap::COL_NAME.') LIKE ?', $term, \PDO::PARAM_STR)
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
                ->where(SpyProductCategoryTableMap::COL_FK_CATEGORY.' <> ?', $idExcludedCategory, \PDO::PARAM_INT);
        }

        return $query;
    }

}
