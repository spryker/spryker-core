<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\CriteriaExpander;

use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\Filter\StatusProductOfferTableFilterDataProvider;

class StatusFilterProductOfferTableCriteriaExpander implements FilterProductOfferTableCriteriaExpanderInterface
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
        $filterName = StatusProductOfferTableFilterDataProvider::FILTER_NAME;

        if (isset($filters[$filterName])) {
            $productOfferTableCriteriaTransfer->setApprovalStatus(
                strtolower($filters[$filterName])
            );
        }

        return $productOfferTableCriteriaTransfer;
    }
}
