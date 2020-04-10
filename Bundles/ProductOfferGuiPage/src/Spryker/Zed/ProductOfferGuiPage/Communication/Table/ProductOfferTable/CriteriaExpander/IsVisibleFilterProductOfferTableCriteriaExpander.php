<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander;

use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter\IsVisibleProductOfferTableFilterDataProvider;

class IsVisibleFilterProductOfferTableCriteriaExpander implements FilterProductOfferTableCriteriaExpanderInterface
{
    /**
     * @param array $filters
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    public function expandProductOfferTableCriteria(
        array $filters,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): ProductOfferTableCriteriaTransfer {
        $filterName = IsVisibleProductOfferTableFilterDataProvider::FILTER_NAME;

        if (isset($filters[$filterName])) {
            $isVisible = (bool)$filters[$filterName];
            $productOfferTableCriteriaTransfer->setIsVisible($isVisible);
        }

        return $productOfferTableCriteriaTransfer;
    }
}
