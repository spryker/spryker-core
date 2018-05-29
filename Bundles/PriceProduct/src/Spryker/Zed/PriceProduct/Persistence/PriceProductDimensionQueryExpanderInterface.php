<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;

interface PriceProductDimensionQueryExpanderInterface
{
    /**
     * @api
     *
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function expandPriceProductStoreQueryWithPriceDimension(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): SpyPriceProductStoreQuery;

    /**
     * @api
     *
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function expandPriceProductStoreQueryWithPriceDimensionForDelete(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): SpyPriceProductStoreQuery;
}
