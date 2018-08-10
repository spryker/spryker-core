<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueRepositoryInterface;

class MerchantRelationshipThresholdReader implements MerchantRelationshipThresholdReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueRepositoryInterface
     */
    protected $merchantRelationshipMinimumOrderValueRepository;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueRepositoryInterface $merchantRelationshipMinimumOrderValueRepository
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueRepositoryInterface $merchantRelationshipMinimumOrderValueRepository
    ) {
        $this->merchantRelationshipMinimumOrderValueRepository = $merchantRelationshipMinimumOrderValueRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        $customerMerchantRelationships = $this->getCustomerMerchantRelationships($quoteTransfer);
        if (empty($customerMerchantRelationships)) {
            return [];
        }

        $itemMerchantRelationshipSubTotals = $this->getItemsMerchantRelationshipSubTotals($quoteTransfer);

        $cartMerchantRelationshipIds = $this->getCartMerchantRelationshipIds($customerMerchantRelationships, $itemMerchantRelationshipSubTotals);

        $merchantRelationshipMinimumOrderValueTransfers = $this->merchantRelationshipMinimumOrderValueRepository
            ->findThresholdsForMerchantRelationshipIds(
                $quoteTransfer->getStore(),
                $quoteTransfer->getCurrency(),
                $cartMerchantRelationshipIds
            );

        return $this->getMinimumOrderValueTransfers($merchantRelationshipMinimumOrderValueTransfers, $itemMerchantRelationshipSubTotals);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    protected function getCustomerMerchantRelationships(QuoteTransfer $quoteTransfer): array
    {
        if ($this->haveCustomerMerchantRelationships($quoteTransfer)) {
            return $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getCompanyBusinessUnit()->getMerchantRelationships()->getArrayCopy();
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function haveCustomerMerchantRelationships(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getCustomer() &&
            $quoteTransfer->getCustomer()->getCompanyUserTransfer() &&
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getCompanyBusinessUnit() &&
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getCompanyBusinessUnit()->getMerchantRelationships();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function getItemsMerchantRelationshipSubTotals(QuoteTransfer $quoteTransfer): array
    {
        $itemMerchantRelationshipSubTotals = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getPriceProduct() || !$itemTransfer->getPriceProduct()->getPriceDimension()) {
                continue;
            }

            $itemIdMerchantRelationship = $itemTransfer->getPriceProduct()->getPriceDimension()->getIdMerchantRelationship();
            $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] = $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] ?? 0;
            $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] += $itemTransfer->getSumSubtotalAggregation();
        }

        return $itemMerchantRelationshipSubTotals;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer[] $customerMerchantRelationships
     * @param int[] $itemMerchantRelationshipSubTotals
     *
     * @return int[]
     */
    public function getCartMerchantRelationshipIds(array $customerMerchantRelationships, array $itemMerchantRelationshipSubTotals): array
    {
        $cartMerchantRelationshipIds = [];
        foreach ($customerMerchantRelationships as $merchantRelationshipTransfer) {
            if (isset($itemMerchantRelationshipSubTotals[$merchantRelationshipTransfer->getIdMerchantRelationship()])) {
                $cartMerchantRelationshipIds[$merchantRelationshipTransfer->getIdMerchantRelationship()] = $merchantRelationshipTransfer->getIdMerchantRelationship();
            }
        }

        return $cartMerchantRelationshipIds;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer[] $merchantRelationshipMinimumOrderValueTransfers
     * @param int[] $itemMerchantRelationshipSubTotals
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function getMinimumOrderValueTransfers(
        array $merchantRelationshipMinimumOrderValueTransfers,
        array $itemMerchantRelationshipSubTotals
    ): array {
        $minimumOrderValueTransfers = [];
        foreach ($merchantRelationshipMinimumOrderValueTransfers as $merchantRelationshipMinimumOrderValueTransfer) {
            $minimumOrderValueTransfer = $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue();
            $minimumOrderValueTransfer->setSubTotal(
                $itemMerchantRelationshipSubTotals[$merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship()->getIdMerchantRelationship()]
            );
            $minimumOrderValueTransfers[] = $minimumOrderValueTransfer;
        }

        return $minimumOrderValueTransfers;
    }
}
