<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;

class ProductOfferCriteriaTransferProvider implements ProductOfferCriteriaTransferProviderInterface
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaTransfer
     */
    public function createSellableProductOfferCriteriaTransfer(): ProductOfferCriteriaTransfer
    {
        return (new ProductOfferCriteriaTransfer())
            ->setIsActive(true)
            ->setIsActiveMerchant(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
    }
}
