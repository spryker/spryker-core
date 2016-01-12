<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeTypeQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;

class ProductQueryContainer extends AbstractQueryContainer implements ProductQueryContainerInterface
{

    /**
     * @todo CD-427 Follow naming conventions and use method name starting with 'query*'
     *
     * @param string $skus
     * @param LocaleTransfer $locale
     *
     * @return SpyProductQuery
     */
    public function getProductWithAttributeQuery($skus, LocaleTransfer $locale)
    {
        $query = SpyProductQuery::create();
        $query->filterBySku($skus);
        $query->useSpyProductLocalizedAttributesQuery()
                    ->filterByFkLocale($locale->getIdLocale())
                ->endUse()

            ->addSelectColumn(SpyProductTableMap::COL_SKU)
            ->addSelectColumn(SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addSelectColumn(SpyProductLocalizedAttributesTableMap::COL_NAME)
            ->addAsColumn('sku', SpyProductTableMap::COL_SKU)
            ->addAsColumn('attributes', SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addAsColumn('name', SpyProductLocalizedAttributesTableMap::COL_NAME);

        return $query;
    }

    /**
     * @param string $concreteSku
     * @param int $idLocale
     *
     * @return SpyProductQuery
     */
    public function queryProductWithAttributesAndProductAbstract($concreteSku, $idLocale)
    {
        $query = SpyProductQuery::create();

        $query->filterBySku($concreteSku)
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->useSpyProductAbstractQuery()
            ->endUse();

        return $query;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return SpyTaxSetQuery
     */
    public function queryTaxSetForProductAbstract($idProductAbstract)
    {
        return SpyTaxSetQuery::create()
            ->useSpyProductAbstractQuery()
                ->filterByIdProductAbstract($idProductAbstract)
            ->endUse();
    }

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryProductAbstract()
    {
        return SpyProductAbstractQuery::create();
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
     * @return SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku)
    {
        return SpyProductAbstractQuery::create()
            ->filterBySku($sku);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return SpyProductAbstractQuery
     */
    public function querySkuFromProductAbstractById($idProductAbstract)
    {
        return SpyProductAbstractQuery::create()
            ->filterByIdProductAbstract($idProductAbstract);
    }

    /**
     * @throws PropelException
     *
     * @return ModelCriteria
     */
    public function queryAbstractSkuForm()
    {
        return SpyProductAbstractQuery::create()
            ->select([
                SpyProductAbstractTableMap::COL_SKU => 'value',
                SpyProductAbstractTableMap::COL_SKU => 'label',
            ])
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, 'value')
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, 'label');
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
     * @param int $idProductAbstract
     * @param int $fkCurrentLocale
     *
     * @return SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractAttributeCollection($idProductAbstract, $fkCurrentLocale)
    {
        $query = SpyProductAbstractLocalizedAttributesQuery::create();
        $query
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkLocale($fkCurrentLocale);

        return $query;
    }

    /**
     * @param int $idConcreteProduct
     * @param int $fkCurrentLocale
     *
     * @return SpyProductLocalizedAttributesQuery
     */
    public function queryConcreteProductAttributeCollection($idConcreteProduct, $fkCurrentLocale)
    {
        $query = SpyProductLocalizedAttributesQuery::create();
        $query
            ->filterByFkProduct($idConcreteProduct)
            ->filterByFkLocale($fkCurrentLocale);

        return $query;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return self
     */
    public function joinConcreteProducts(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoinObject(
                new Join(
                    SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                    SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
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
     * @return self
     */
    public function joinProductQueryWithLocalizedAttributes(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $expandableQuery
            ->addJoin(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
                Criteria::INNER_JOIN
            );

        $expandableQuery
            ->addJoin(
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
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
                    SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                    SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT,
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
                    SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT,
                    Criteria::INNER_JOIN
                ),
                'productAttributesJoin'
            );

        $expandableQuery->addJoinCondition(
            'productAttributesJoin',
            SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE . ' = ' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );

        $expandableQuery->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, 'id_product_abstract');

        $expandableQuery->withColumn(
            SpyProductAbstractTableMap::COL_ATTRIBUTES,
            'abstract_attributes'
        );
        $expandableQuery->withColumn(
            SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES,
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
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            'abstract_name'
        );
        $expandableQuery->withColumn(
            'GROUP_CONCAT(spy_product_localized_attributes.name)',
            'concrete_names'
        );

        return $this;
    }

    // @todo refactor queries from below

    public function queryConcreteProductByProductAbstract(SpyProductAbstract $productAbstract)
    {
        return SpyProductQuery::create()
            ->filterByFkProductAbstract($productAbstract->getIdProductAbstract());
    }

}
