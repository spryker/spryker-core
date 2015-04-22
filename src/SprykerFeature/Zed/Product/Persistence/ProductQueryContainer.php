<?php

namespace SprykerFeature\Zed\Product\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedAbstractProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributesMetadataQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributeTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyUrlTableMap;

class ProductQueryContainer extends AbstractQueryContainer implements ProductQueryContainerInterface
{
    /**
     * @param string $skus
     * @param int $localeId
     * 
     * @return SpyProductQuery
     */
    public function getProductWithAttributeQuery($skus, $localeId)
    {
        $query = SpyProductQuery::create();
        $query->filterBySku($skus);
        $query->useSpyLocalizedProductAttributesQuery()
                    ->filterByFkLocale($localeId)
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
     * @param string $sku
     * @return SpyProductQuery
     */
    public function queryConcreteProductBySku($sku)
    {
        return SpyProductQuery::create()
            ->filterBySku($sku);
    }

    /**
     * @param string $sku
     * @return SpyAbstractProductQuery
     */
    public function queryAbstractProductBySku($sku)
    {
        return SpyAbstractProductQuery::create()
            ->filterBySku($sku);
    }

    /**
     * @param $idAbstractProduct
     * 
     * @return SpyAbstractProductQuery
     */
    public function querySkuFromAbstractProductById($idAbstractProduct)
    {
        return SpyAbstractProductQuery::create()
            ->filterByIdAbstractProduct($idAbstractProduct);
    }

    /**
     * @return ModelCriteria
     * @throws PropelException
     */
    public function queryAbstractSkuForm()
    {
        return SpyAbstractProductQuery::create()
            ->select([
                SpyAbstractProductTableMap::COL_SKU => 'value',
                SpyAbstractProductTableMap::COL_SKU => 'label'
            ])
            ->withColumn(SpyAbstractProductTableMap::COL_SKU, 'value')
            ->withColumn(SpyAbstractProductTableMap::COL_SKU, 'label')
            ;
    }

    /**
     * @return ModelCriteria
     * @throws PropelException
     */
    public function queryConcreteSkuForm()
    {
        return $query = SpyProductQuery::create()
            ->select([
                SpyProductTableMap::COL_SKU => 'value',
                SpyProductTableMap::COL_SKU => 'label'
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
     * @return ModelCriteria
     */
    public function joinLocalizedProductQueryWithAttributes(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoin(
                SpyProductTableMap::COL_FK_ABSTRACT_PRODUCT,
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                Criteria::INNER_JOIN
            );

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

        $expandableQuery
            ->addJoinObject(
                (new Join(
                    SpyProductTableMap::COL_ID_PRODUCT,
                    SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ID,
                    Criteria::LEFT_JOIN
                ))->setRightTableAlias('product_urls')
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

        $expandableQuery->withColumn(SpyProductTableMap::COL_ID_PRODUCT, 'id_product');
        $expandableQuery->withColumn(SpyProductTableMap::COL_SKU, 'sku');
        $expandableQuery->withColumn(
            SpyLocalizedProductAttributesTableMap::COL_ATTRIBUTES,
            'attributes'
        );
        $expandableQuery->withColumn(
            'product_urls.url',
            'product_url'
        );
        $expandableQuery->withColumn(
            SpyLocalizedProductAttributesTableMap::COL_NAME,
            'name'
        );
        $expandableQuery->withColumn(
            SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
            'abstract_attributes'
        );
        $expandableQuery->withColumn(
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
            'abstract_name'
        );

        return $expandableQuery;
    }
}
