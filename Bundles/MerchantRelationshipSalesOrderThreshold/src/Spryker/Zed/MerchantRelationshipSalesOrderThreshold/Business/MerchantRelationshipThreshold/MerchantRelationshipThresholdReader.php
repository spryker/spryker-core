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
use Spryker\Shared\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdConfig;
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
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    public function findApplicableThresholds(QuoteTransfer $quoteTransfer): array
    {
        $this->assertRequiredAttributes($quoteTransfer);
        $customerMerchantRelationships = $this->getCustomerMerchantRelationships($quoteTransfer);

        if (empty($customerMerchantRelationships)) {
            return [];
        }

        $itemMerchantRelationshipSubTotals = $this->getItemsMerchantRelationshipSubTotals($quoteTransfer);

        if (empty($itemMerchantRelationshipSubTotals)) {
            return [];
        }

        $cartMerchantRelationshipIds = $this->getCartMerchantRelationshipIds($customerMerchantRelationships, $itemMerchantRelationshipSubTotals);

        $merchantRelationshipSalesOrderThresholdTransfers = $this->merchantRelationshipSalesOrderThresholdRepository
            ->getMerchantRelationshipSalesOrderThresholds(
                $quoteTransfer->getStore(),
                $quoteTransfer->getCurrency(),
                $cartMerchantRelationshipIds
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
        $quoteTransfer->requireStore()->requireCurrency();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int[] $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer[]
     */
    public function getMerchantRelationshipSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        array $merchantRelationshipIds
    ): array {
        $merchantRelationshipSalesOrderThresholdTransfers = $this->merchantRelationshipSalesOrderThresholdRepository->getMerchantRelationshipSalesOrderThresholds(
            $storeTransfer,
            $currencyTransfer,
            $merchantRelationshipIds
        );

        foreach ($merchantRelationshipSalesOrderThresholdTransfers as $merchantRelationshipSalesOrderThresholdTransfer) {
            $this->translationReader->hydrateLocalizedMessages($merchantRelationshipSalesOrderThresholdTransfer);
        }

        return $merchantRelationshipSalesOrderThresholdTransfers;
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
        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            if (!$this->isMerchantRelationshipItem($itemTransfer)) {
                continue;
            }

            $itemIdMerchantRelationship = $itemTransfer->getPriceProduct()->getPriceDimension()->getIdMerchantRelationship();
            $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] = $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] ?? 0;
            $itemMerchantRelationshipSubTotals[$itemIdMerchantRelationship] += $this->getItemSumSubtotalAggregation($itemTransfer, $quoteTransfer);
        }

        return $itemMerchantRelationshipSubTotals;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getItemSumSubtotalAggregation(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): int
    {
        $itemSubTotal = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if ($quoteTransfer->getPriceMode() === MerchantRelationshipSalesOrderThresholdConfig::PRICE_MODE_NET) {
                $itemSubTotal += $productOptionTransfer->getUnitNetPrice() * $productOptionTransfer->getQuantity();
                continue;
            }

            $itemSubTotal += $productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity();
        }

        if ($quoteTransfer->getPriceMode() === MerchantRelationshipSalesOrderThresholdConfig::PRICE_MODE_NET) {
            $itemSubTotal += $itemTransfer->getUnitNetPrice() * $itemTransfer->getQuantity();
        }

        $itemSubTotal += $itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity();

        return $itemSubTotal;
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
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer[] $merchantRelationshipSalesOrderThresholdTransfers
     * @param int[] $itemMerchantRelationshipSubTotals
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    protected function getSalesOrderThresholdTransfers(
        array $merchantRelationshipSalesOrderThresholdTransfers,
        array $itemMerchantRelationshipSubTotals
    ): array {
        $salesOrderThresholdTransfers = [];
        foreach ($merchantRelationshipSalesOrderThresholdTransfers as $merchantRelationshipSalesOrderThresholdTransfer) {
            $salesOrderThresholdTransfer = $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue();
            $salesOrderThresholdTransfer->setValue(
                $itemMerchantRelationshipSubTotals[$merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship()->getIdMerchantRelationship()]
            );
            $salesOrderThresholdTransfers[] = $salesOrderThresholdTransfer;
        }

        return $salesOrderThresholdTransfers;
    }
}
