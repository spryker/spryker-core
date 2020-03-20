<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchPersistenceFactory getFactory()
 */
class ProductSearchQueryContainer extends AbstractQueryContainer implements ProductSearchQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryExportableProductsByLocale(array $productIds, LocaleTransfer $locale)
    {
        $query = $this->getFactory()->createProductQuery();
        $query
            ->filterByIdProduct($productIds, Criteria::IN)
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($locale->getIdLocale())
            ->endUse()
            ->addSelectColumn(SpyProductTableMap::COL_SKU)
            ->addSelectColumn(SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES)
            ->addSelectColumn(SpyProductLocalizedAttributesTableMap::COL_NAME);
        $query
            ->useSpyProductAbstractQuery()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                        ->filterByFkLocale($locale->getIdLocale())
                ->endUse()
            ->endUse()
            ->addAsColumn(
                'abstract_attributes',
                SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES
            );

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function queryByProductAndLocale($idProduct, $idLocale)
    {
        $productSearchQuery = $this->getFactory()->createProductSearchQuery();
        $productSearchQuery
            ->filterByFkProduct($idProduct)
            ->filterByFkLocale($idLocale);

        return $productSearchQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMap()
    {
        $productSearchAttributeMapQuery = $this
            ->getFactory()
            ->createProductSearchAttributeMapQuery()
            ->joinWith('SpyProductAttributeKey');

        return $productSearchAttributeMapQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $fkProductAttributeKey
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMapByFkProductAttributeKey($fkProductAttributeKey)
    {
        $productSearchAttributeMapQuery = $this
            ->getFactory()
            ->createProductSearchAttributeMapQuery()
            ->filterByFkProductAttributeKey($fkProductAttributeKey);

        return $productSearchAttributeMapQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function querySearchPreferencesTable()
    {
        $query = $this
            ->getFactory()
            ->createProductAttributeKeyQuery();

        $query->setIdentifierQuoting(true);

        $this
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_FULL_TEXT, PageIndexMap::FULL_TEXT)
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_FULL_TEXT_BOOSTED, PageIndexMap::FULL_TEXT_BOOSTED)
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_SUGGESTION_TERMS, PageIndexMap::SUGGESTION_TERMS)
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_COMPLETION_TERMS, PageIndexMap::COMPLETION_TERMS);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryFilterPreferencesTable()
    {
        $query = $this
            ->getFactory()
            ->createProductSearchAttributeQuery()
            ->joinWith('SpyProductAttributeKey');

        return $query;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $query
     * @param string $alias
     * @param string $targetField
     *
     * @return $this
     */
    protected function leftJoinProductSearchAttributeMap(SpyProductAttributeKeyQuery $query, $alias, $targetField)
    {
        $quotedAlias = $query->quoteIdentifier($alias);

        $query
            ->joinSpyProductSearchAttributeMap($quotedAlias, Criteria::LEFT_JOIN)
            ->addJoinCondition($quotedAlias, $alias . '.target_field = ?', $targetField, null, PDO::PARAM_STR)
            ->withColumn($alias . '.target_field IS NOT NULL', $alias);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->getFactory()->createProductAttributeKeyQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryProductSearchAttribute()
    {
        return $this
            ->getFactory()
            ->createProductSearchAttributeQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryAllProductAttributeKeys()
    {
        return $this
            ->queryProductAttributeJoinProductSearchAttribute()
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryUnusedProductAttributeKeys()
    {
        return $this
            ->queryProductAttributeJoinProductSearchAttribute()
            ->filterByIdProductSearchAttribute(null)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param bool $synced
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    public function queryProductSearchAttributeBySynced($synced)
    {
        return $this
            ->getFactory()
            ->createProductSearchAttributeQuery()
            ->filterBySynced($synced);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeArchiveQuery
     */
    public function queryProductSearchAttributeArchive()
    {
        return $this
            ->getFactory()
            ->createProductSearchAttributeArchiveQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param bool $synced
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMapBySynced($synced)
    {
        return $this
            ->getFactory()
            ->createProductSearchAttributeMapQuery()
            ->filterBySynced($synced);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapArchiveQuery
     */
    public function queryProductSearchAttributeMapArchive()
    {
        return $this
            ->getFactory()
            ->createProductSearchAttributeMapArchiveQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $attributeNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractByAttributeName(array $attributeNames)
    {
        $query = $this->getFactory()
            ->createProductAbstractQuery()
            ->leftJoinSpyProductAbstractLocalizedAttributes()
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinSpyProductLocalizedAttributes()
            ->endUse();

        $query->setDistinct();

        foreach ($attributeNames as $attributeName) {
            $this->addAttributeOrConditions($query, $attributeName);
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function queryProductSearch()
    {
        return $this->getFactory()->createProductSearchQuery();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param string $attributeName
     *
     * @return void
     */
    protected function addAttributeOrConditions(SpyProductAbstractQuery $query, $attributeName)
    {
        $condition = '%"' . str_replace('_', '\_', $attributeName) . '":%';

        $query
            ->addOr(SpyProductAbstractTableMap::COL_ATTRIBUTES, $condition, Criteria::LIKE)
            ->addOr(SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES, $condition, Criteria::LIKE)
            ->addOr(SpyProductTableMap::COL_ATTRIBUTES, $condition, Criteria::LIKE)
            ->addOr(SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES, $condition, Criteria::LIKE);
    }

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery
     */
    protected function queryProductAttributeJoinProductSearchAttribute()
    {
        return $this
            ->queryProductAttributeKey()
            ->addSelectColumn(SpyProductAttributeKeyTableMap::COL_KEY)
            ->useSpyProductSearchAttributeQuery(null, Criteria::LEFT_JOIN);
    }
}
