<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductQueryContainer extends AbstractQueryContainer implements ProductQueryContainerInterface
{

    /**
     * @api
     *
     * @param string $concreteSku
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductWithAttributesAndProductAbstract($concreteSku, $idLocale)
    {
        $query = $this->getFactory()->createProductQuery();

        $query->filterBySku($concreteSku)
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->useSpyProductAbstractQuery()
            ->endUse();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetForProductAbstract($idProductAbstract)
    {
        return $this->getFactory()->createTaxSetQuery()
            ->useSpyProductAbstractQuery()
                ->filterByIdProductAbstract($idProductAbstract)
            ->endUse();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract()
    {
        return $this->getFactory()->createProductAbstractQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct()
    {
        return $this->getFactory()->createProductQuery();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedAttributes($idProductAbstract)
    {
        $query = $this->getFactory()->createProductAbstractLocalizedAttributesQuery();
        $query->filterByFkProductAbstract($idProductAbstract);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductLocalizedAttributes($idProduct)
    {
        $query = $this->getFactory()->createProductLocalizedAttributesQuery();
        $query->filterByFkProduct($idProduct);

        return $query;
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteBySku($sku)
    {
        return $this->getFactory()->createProductQuery()
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySkuFromProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->filterByIdProductAbstract($idProductAbstract);
    }

    /**
     * @api
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAbstractSkuForm()
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->select([
                SpyProductAbstractTableMap::COL_SKU => 'value',
                SpyProductAbstractTableMap::COL_SKU => 'label',
            ])
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, 'value')
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, 'label');
    }

    /**
     * @api
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryConcreteSkuForm()
    {
        return $this->getFactory()->createProductQuery()
            ->select([
                SpyProductTableMap::COL_SKU => 'value',
                SpyProductTableMap::COL_SKU => 'label',
            ])
            ->withColumn(SpyProductTableMap::COL_SKU, 'value')
            ->withColumn(SpyProductTableMap::COL_SKU, 'label');
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery
     */
    public function queryAttributesMetadata()
    {
        return $this->getFactory()
            ->createProductAttributesMetadataQuery()
            ->orderByKey();
    }

    /**
     * @api
     *
     * @param string $attributeName
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery
     */
    public function queryAttributeByName($attributeName)
    {
        $query = $this->getFactory()->createProductAttributesMetadataQuery();
        $query->filterByKey($attributeName);

        return $query;
    }

    /**
     * @api
     *
     * @param string $attributeType
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeTypeQuery
     */
    public function queryAttributeTypeByName($attributeType)
    {
        $query = $this->getFactory()->createProductAttributeTypeQuery();
        $query->filterByName($attributeType);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $fkCurrentLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractAttributeCollection($idProductAbstract, $fkCurrentLocale)
    {
        $query = $this->getFactory()->createProductAbstractLocalizedAttributesQuery();
        $query
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkLocale($fkCurrentLocale);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductConcrete
     * @param int $fkCurrentLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductConcreteAttributeCollection($idProductConcrete, $fkCurrentLocale)
    {
        $query = $this->getFactory()->createProductLocalizedAttributesQuery();
        $query
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkLocale($fkCurrentLocale);

        return $query;
    }

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinProductConcreteCollection(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoinObject(
                new Join(
                    SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                    SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                    Criteria::LEFT_JOIN
                ),
                'productConcreteJoin'
            );

        $expandableQuery->addJoinCondition(
            'productConcreteJoin',
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
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return $this
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

    /**
     * @api
     *
     * @todo refactor queries from below
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteByProductAbstract(SpyProductAbstract $productAbstract)
    {
        return $this->getFactory()->createProductQuery()
            ->filterByFkProductAbstract($productAbstract->getIdProductAbstract());
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->getFactory()->createProductAttributeKeyQuery();
    }

}
