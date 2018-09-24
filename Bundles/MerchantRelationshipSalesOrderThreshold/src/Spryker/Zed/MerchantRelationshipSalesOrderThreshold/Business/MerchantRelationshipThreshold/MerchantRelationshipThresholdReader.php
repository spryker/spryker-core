<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer;
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
     * @param \Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer[]
     */
    public function findApplicableThresholds(SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer): array
    {
        $this->assertRequiredAttributes($salesOrderThresholdQuoteTransfer);
        $customerMerchantRelationships = $this->getCustomerMerchantRelationships($salesOrderThresholdQuoteTransfer->getOriginalQuote());

        if (empty($customerMerchantRelationships)) {
            return [];
        }

        $itemMerchantRelationshipSubTotals = $this->getItemsMerchantRelationshipSubTotals($salesOrderThresholdQuoteTransfer);

        if (empty($itemMerchantRelationshipSubTotals)) {
            return [];
        }

        $cartMerchantRelationshipIds = $this->getCartMerchantRelationshipIds($customerMerchantRelationships, $itemMerchantRelationshipSubTotals);

        $merchantRelationshipSalesOrderThresholdTransfers = $this->merchantRelationshipSalesOrderThresholdRepository
            ->getMerchantRelationshipSalesOrderThresholds(
                $salesOrderThresholdQuoteTransfer->getOriginalQuote()->getStore(),
                $salesOrderThresholdQuoteTransfer->getOriginalQuote()->getCurrency(),
                $cartMerchantRelationshipIds
            );

        $this->filterOutThresholdItems($salesOrderThresholdQuoteTransfer, $merchantRelationshipSalesOrderThresholdTransfers);

        return $this->getSalesOrderThresholdTransfers($merchantRelationshipSalesOrderThresholdTransfers, $itemMerchantRelationshipSubTotals);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer
     *
     * @return void
     */
    protected function assertRequiredAttributes(SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer): void
    {
        $salesOrderThresholdQuoteTransfer
            ->requireOriginalQuote()
            ->getOriginalQuote()
                ->requireStore()
                ->requireCurrency();
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
     * @param \Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer
     *
     * @return int[]
     */
    protected function getItemsMerchantRelationshipSubTotals(SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer): array
    {
        $itemMerchantRelationshipSubTotals = [];
        foreach ($salesOrderThresholdQuoteTransfer->getThresholdItems() as $key => $itemTransfer) {
            if (!$this->isMerchantRelationshipItem($itemTransfer)) {
                continue;
            }

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
     * @param \Generated\Shared\Transfer\SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer[] $merchantRelationshipSalesOrderThresholdTransfers
     *
     * @return void
     */
    protected function filterOutThresholdItems(
        SalesOrderThresholdQuoteTransfer $salesOrderThresholdQuoteTransfer,
        array $merchantRelationshipSalesOrderThresholdTransfers
    ): void {
        $thresholdMerchantRelationshipIds = [];
        foreach ($merchantRelationshipSalesOrderThresholdTransfers as $merchantRelationshipSalesOrderThresholdTransfer) {
            $idMerchantRelationship = $merchantRelationshipSalesOrderThresholdTransfer->getMerchantRelationship()->getIdMerchantRelationship();
            $thresholdMerchantRelationshipIds[$idMerchantRelationship] = $idMerchantRelationship;
        }

        $salesOrderThresholdQuoteTransfer->getThresholdItems()->exchangeArray(
            array_filter(
                $salesOrderThresholdQuoteTransfer->getThresholdItems()->getArrayCopy(),
                function (ItemTransfer $itemTransfer) use ($thresholdMerchantRelationshipIds) {
                    return !$this->isMerchantRelationshipItem($itemTransfer) ||
                        !isset($thresholdMerchantRelationshipIds[$itemTransfer->getPriceProduct()->getPriceDimension()->getIdMerchantRelationship()]);
                }
            )
        );
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
