<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\StoreProductOfferTableFilter;

class StoreProductOfferCriteriaFilterExpander implements ProductOfferCriteriaFilterExpanderInterface
{
    /**
     * @param string $filterName
     *
     * @return bool
     */
    public function isApplicable(string $filterName): bool
    {
        return $filterName === StoreProductOfferTableFilter::FILTER_NAME;
    }

    /**
     * @param mixed $filterValue
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    public function expandProductOfferCriteriaFilter(
        $filterValue,
        ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
    ): ProductOfferCriteriaFilterTransfer {
        $storeIds = array_map(function ($storeId) {
            return (int)$storeId;
        }, $filterValue);

        $productOfferCriteriaFilterTransfer->setInStores($storeIds);

        return $productOfferCriteriaFilterTransfer;
    }
}
