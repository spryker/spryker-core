<?php

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;

class ProductOfferCriteriaFilterTransferFactory
{
    protected const STATUS_APPROVED = 'approved';

    /**
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    public function createProductOfferCriteriaFilterTransfer(): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setIsActive(true)
            ->setIsActiveMerchant(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    public function createIncorrectProductOfferCriteriaFilterTransfer(): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setIsActive(false)
            ->setIsActiveMerchant(false);
    }
}
