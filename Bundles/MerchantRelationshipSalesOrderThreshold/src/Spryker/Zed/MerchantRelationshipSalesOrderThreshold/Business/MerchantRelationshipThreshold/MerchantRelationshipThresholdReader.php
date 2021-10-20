<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationReaderInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface;

class MerchantRelationshipThresholdReader implements MerchantRelationshipThresholdReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface
     */
    protected $merchantRelationshipSalesOrderThresholdRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationReaderInterface
     */
    protected $translationReader;

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface $merchantRelationshipSalesOrderThresholdRepository
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationReaderInterface $translationReader
     */
    public function __construct(
        MerchantRelationshipSalesOrderThresholdRepositoryInterface $merchantRelationshipSalesOrderThresholdRepository,
        MerchantRelationshipSalesOrderThresholdTranslationReaderInterface $translationReader
    ) {
        $this->merchantRelationshipSalesOrderThresholdRepository = $merchantRelationshipSalesOrderThresholdRepository;
        $this->translationReader = $translationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer>
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        $customerMerchantRelationships = $this->getCustomerMerchantRelationships($quoteTransfer);

        if (empty($customerMerchantRelationships)) {
            return [];
        }

        $itemMerchantRelationshipSubTotals = $this->getItemsMerchantRelationshipSubTotals($quoteTransfer);

        if (empty($itemMerchantRelationshipSubTotals)) {
            return [];
        }

        $cartMerchantRelationshipIds = $this->getCartMerchantRelationshipIds($customerMerchantRelationships, $itemMerchantRelationshipSubTotals);

        $this->assertRequiredAttributes($quoteTransfer);
        $merchantRelationshipSalesOrderThresholdTransfers = $this->merchantRelationshipSalesOrderThresholdRepository
            ->getMerchantRelationshipSalesOrderThresholds(
                $quoteTransfer->getStore(),
                $quoteTransfer->getCurrency(),
                $cartMerchantRelationshipIds,
            );

        return $this->getSalesOrderThresholdTransfers($merchantRelationshipSalesOrderThresholdTransfers, $itemMerchantRelationshipSubTotals);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer
            ->requireStore()
            ->requireCurrency();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param array<int> $merchantRelationshipIds
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer>
     */
    public function getMerchantRelationshipSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $merchantRelationshipIds
    ): array {
        $merchantRelationshipSalesOrderThresholdTransfers = $this->merchantRelationshipSalesOrderThresholdRepository
            ->getMerchantRelationshipSalesOrderThresholds(
                $storeTransfer,
                $currencyTransfer,
                $merchantRelationshipIds,
            );

        foreach ($merchantRelationshipSalesOrderThresholdTransfers as $merchantRelationshipSalesOrderThresholdTransfer) {
            $this->translationReader->hydrateLocalizedMessages($merchantRelationshipSalesOrderThresholdTransfer);
        }

        return $merchantRelationshipSalesOrderThresholdTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
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
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getCompanyBusinessUnit()->getMerchantRelationships()->count();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int>
     */
    protected function getItemsMerchantRelationshipSubTotals(QuoteTransfer $quoteTransfer): array
    {
        $itemMerchantRelationshipSubTotals = [];
        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            if (!$this->isMerchantRelationshipItem($itemTransfer)) {
                continue;
            }

            $itemTransfer->requireSumSubtotalAggregation();
            $itemIdMerchantRelationship = $itemTransfer->getPriceProduct()->getPriceDimension()->getIdMerchantRelationship();
            $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] = $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] ?? 0;
            $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] += $itemTransfer->getSumSubtotalAggregation();
        }

        return $itemMerchantRelationshipSubTotals;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isMerchantRelationshipItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getPriceProduct() &&
            $itemTransfer->getPriceProduct()->getPriceDimension() &&
            $itemTransfer->getPriceProduct()->getPriceDimension()->getIdMerchantRelationship();
    }

    /**
     * @param array<\Generated\Shared\Transfer\MerchantRelationshipTransfer> $customerMerchantRelationships
     * @param array<int> $itemMerchantRelationshipSubTotals
     *
     * @return array<int>
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
     * @param array<\Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer> $merchantRelationshipSalesOrderThresholdTransfers
     * @param array<int> $itemMerchantRelationshipSubTotals
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdValueTransfer>
     */
    protected function getSalesOrderThresholdTransfers(
        array $merchantRelationshipSalesOrderThresholdTransfers,
        array $itemMerchantRelationshipSubTotals
    ): array {
        $salesOrderThresholdTransfers = [];
        foreach ($merchantRelationshipSalesOrderThresholdTransfers as $merchantRelationshipSalesOrderThresholdTransfer) {
            $salesOrderThresholdTransfer = $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue();
            $salesOrderThresholdTransfer->setValue(
                $itemMerchantRelationshipSubTotals[$merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship()->getIdMerchantRelationship()],
            );
            $salesOrderThresholdTransfers[] = $salesOrderThresholdTransfer;
        }

        return $salesOrderThresholdTransfers;
    }
}
