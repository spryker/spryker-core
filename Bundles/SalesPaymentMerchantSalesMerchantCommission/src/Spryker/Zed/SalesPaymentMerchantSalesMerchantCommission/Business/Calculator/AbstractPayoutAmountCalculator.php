<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Exception\BaseAmountFieldNotSetException;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig;

abstract class AbstractPayoutAmountCalculator implements PayoutAmountCalculatorStrategyInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig
     */
    protected SalesPaymentMerchantSalesMerchantCommissionConfig $config;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig $config
     */
    public function __construct(
        SalesPaymentMerchantSalesMerchantCommissionConfig $config
    ) {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getPriceMode(OrderTransfer $orderTransfer): string
    {
        $priceMode = $orderTransfer->getPriceModeOrFail();
        if ($priceMode === $this->config::PRICE_MODE_NET) {
            return $this->config::PRICE_MODE_NET;
        }

        return $this->config::PRICE_MODE_GROSS;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $payoutAmount
     *
     * @return int
     */
    protected function applyCommission(ItemTransfer $itemTransfer, int $payoutAmount): int
    {
        $commissionAmount = $itemTransfer->getMerchantCommissionAmountFullAggregation() ?? 0;

        return $payoutAmount - $commissionAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $payoutAmount
     *
     * @return int
     */
    protected function applyReverseCommission(ItemTransfer $itemTransfer, int $payoutAmount): int
    {
        $commissionRefundedAmount = $itemTransfer->getMerchantCommissionRefundedAmount() ?? 0;

        return $payoutAmount - $commissionRefundedAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $payoutAmount
     *
     * @return int
     */
    protected function applyTaxDeduction(
        ItemTransfer $itemTransfer,
        OrderTransfer $orderTransfer,
        int $payoutAmount
    ): int {
        $isTaxDeductionEnabledForStoreAndPriceMode = $this->config->isTaxDeductionEnabledForStoreAndPriceMode(
            $orderTransfer->getStoreOrFail(),
            $orderTransfer->getPriceModeOrFail(),
        );

        if ($isTaxDeductionEnabledForStoreAndPriceMode) {
            return $payoutAmount - $itemTransfer->getSumTaxAmountFullAggregationOrFail();
        }

        return $payoutAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $payoutAmount
     *
     * @return int
     */
    protected function applyReverseTaxDeduction(
        ItemTransfer $itemTransfer,
        OrderTransfer $orderTransfer,
        int $payoutAmount
    ): int {
        $isTaxDeductionEnabledForStoreAndPriceMode = $this->config->isTaxDeductionEnabledForStoreAndPriceMode(
            $orderTransfer->getStoreOrFail(),
            $orderTransfer->getPriceModeOrFail(),
        );

        if ($isTaxDeductionEnabledForStoreAndPriceMode) {
            return $payoutAmount - $itemTransfer->getTaxAmountAfterCancellationOrFail();
        }

        return $payoutAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Exception\BaseAmountFieldNotSetException
     *
     * @return int
     */
    protected function getBaseReverseAmount(ItemTransfer $itemTransfer): int
    {
        $baseAmountField = $this->config->getBaseAmountFieldForReversePayout();
        if (!$itemTransfer->offsetExists($baseAmountField)) {
            throw new BaseAmountFieldNotSetException('Base amount field for reverse payout is not set.');
        }

        return $itemTransfer->offsetGet($baseAmountField);
    }
}
