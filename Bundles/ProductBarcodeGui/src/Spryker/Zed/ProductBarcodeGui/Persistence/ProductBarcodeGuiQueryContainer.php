<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductBarcodeGui\Persistence\ProductBarcodeGuiPersistenceFactory getFactory()
 */
class ProductBarcodeGuiQueryContainer extends AbstractQueryContainer implements ProductBarcodeGuiQueryContainerInterface
{
    /**
     * @api
     *
     * @uses SpyProductQuery
     * @uses SpyProductLocalizedAttributesQuery
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function prepareTableQuery(LocaleTransfer $localeTransfer): SpyProductQuery
    {
        $localeTransfer->requireIdLocale();

        $query = SpyProductQuery::create()
            ->innerJoinSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_PRODUCT_NAME);

        return $query;
    }
}
