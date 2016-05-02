<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

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

}
