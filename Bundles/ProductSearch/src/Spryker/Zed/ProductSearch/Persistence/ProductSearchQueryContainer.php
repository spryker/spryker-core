<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeMappingTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributesOperationTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchPersistenceFactory getFactory()
 */
class ProductSearchQueryContainer extends AbstractQueryContainer implements ProductSearchQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperationQuery
     */
    public function queryFieldOperations()
    {
        $fieldOperations = $this->getFactory()->createProductSearchAttributesOperationQuery()
            ->joinWith('SpyProductAttributesMetadata')
            ->addAscendingOrderByColumn(
                SpyProductSearchAttributesOperationTableMap::COL_SOURCE_ATTRIBUTE_ID
            )
            ->addAscendingOrderByColumn(
                SpyProductSearchAttributesOperationTableMap::COL_WEIGHTING
            );

        return $fieldOperations;
    }

    /**
     * @api
     *
     * @todo CD-427 Follow naming conventions and use method name starting with 'query*'
     *
     * @param array $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, LocaleTransfer $locale)
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
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperationQuery
     */
    public function queryAttributeOperation($idAttribute, $copyTarget)
    {
        $query = $this->getFactory()->createProductSearchAttributesOperationQuery();
        $query
            ->filterBySourceAttributeId($idAttribute)
            ->filterByTargetField($copyTarget);

        return $query;
    }

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     * TODO: can be removed along with ProductSearchQueryExpander and others
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $productSearchQueryExpander = $this->getFactory()->createProductSearchQueryExpander();

        return $productSearchQueryExpander->expandProductQuery($expandableQuery, $locale);
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
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMappingQuery
     */
    public function queryProductSearchAttributeMapping()
    {
        $productSearchAttributeMappingQuery = $this
            ->getFactory()
            ->createProductSearchAttributeMappingQuery()
            ->joinWith('SpyProductAttributesMetadata')
            ->addDescendingOrderByColumn(SpyProductSearchAttributeMappingTableMap::COL_WEIGHTING);

        return $productSearchAttributeMappingQuery;
    }

}
