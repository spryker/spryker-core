<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander;

use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\IsActiveProductOfferTableFilter;

class IsActiveProductOfferTableCriteriaExpander implements ProductOfferTableCriteriaExpanderInterface
{
    /**
     * @param string $filterName
     *
     * @return bool
     */
    public function isApplicable(string $filterName): bool
    {
        return $filterName === IsActiveProductOfferTableFilter::FILTER_NAME;
    }

    /**
     * @param mixed $filterValue
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    public function expandProductOfferTableCriteria(
        $filterValue,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): ProductOfferTableCriteriaTransfer {
        $isActive = (bool)$filterValue;
        $productOfferTableCriteriaTransfer->setIsActive($isActive);

        return $productOfferTableCriteriaTransfer;
    }
}
