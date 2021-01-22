<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferGuiToProductOfferFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    public function update(ProductOfferTransfer $productOfferTransfer): ProductOfferResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer;

    /**
     * @param string $currentStatus
     *
     * @return string[]
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array;
}
