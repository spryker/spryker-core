<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeTypeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchPersistenceFactory getFactory()
 */
class ProductSearchQueryContainer extends AbstractQueryContainer implements ProductSearchQueryContainerInterface
{

    /**
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
            ->filterByIdProduct($productIds)
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
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMap()
    {
        $productSearchAttributeMapQuery = $this
            ->getFactory()
            ->createProductSearchAttributeMapQuery()
            ->joinWith('SpyProductAttributesMetadata');

        return $productSearchAttributeMapQuery;
    }

    /**
     * @api
     *
     * @param int $fkProductAttributesMetadata
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery
     */
    public function queryProductSearchAttributeMapByFkProductAttributesMetadata($fkProductAttributesMetadata)
    {
        $productSearchAttributeMapQuery = $this
            ->getFactory()
            ->createProductSearchAttributeMapQuery()
            ->filterByFkProductAttributesMetadata($fkProductAttributesMetadata);

        return $productSearchAttributeMapQuery;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery
     */
    public function querySearchPreferencesTable()
    {
        $query = $this
            ->getFactory()
            ->createProductAttributesMetadataQuery()
            ->joinSpyProductAttributeType()
            ->withColumn(SpyProductAttributeTypeTableMap::COL_NAME, SearchPreferencesTable::COL_PROPERTY_TYPE);

        $query->setIdentifierQuoting(true);

        $this
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_FULL_TEXT, PageIndexMap::FULL_TEXT)
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_FULL_TEXT_BOOSTED, PageIndexMap::FULL_TEXT_BOOSTED)
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_SUGGESTION_TERMS, PageIndexMap::SUGGESTION_TERMS)
            ->leftJoinProductSearchAttributeMap($query, SearchPreferencesTable::COL_COMPLETION_TERMS, PageIndexMap::COMPLETION_TERMS);

        return $query;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery $query
     * @param string $alias
     * @param string $targetField
     *
     * @return $this
     */
    protected function leftJoinProductSearchAttributeMap($query, $alias, $targetField)
    {
        $quotedAlias = $query->quoteIdentifier($alias);

        $query
            ->joinSpyProductSearchAttributeMap($quotedAlias, Criteria::LEFT_JOIN)
            ->addJoinCondition($quotedAlias, $alias . '.target_field = ?', $targetField, null, \PDO::PARAM_STR)
            ->withColumn($alias . '.target_field IS NOT NULL', $alias);

        return $this;
    }

}
