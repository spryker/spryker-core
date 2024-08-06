<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Exception\PayoutAmountCalculatorStrategyNotFoundException;

class PayoutAmountCalculatorComposite implements PayoutAmountCalculatorInterface
{
    /**
     * @var array<\Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface>
     */
    protected array $payoutAmountCalculatorStrategies;

    /**
     * @param array<\Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface> $payoutAmountCalculatorStrategies
     */
    public function __construct(array $payoutAmountCalculatorStrategies)
    {
        $this->payoutAmountCalculatorStrategies = $payoutAmountCalculatorStrategies;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int
    {
        $payoutAmountCalculatorStrategy = $this->resolvePayoutAmountCalculatorStrategy($itemTransfer, $orderTransfer);

        return $payoutAmountCalculatorStrategy->calculatePayoutAmount($itemTransfer, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Exception\PayoutAmountCalculatorStrategyNotFoundException
     *
     * @return \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface
     */
    protected function resolvePayoutAmountCalculatorStrategy(
        ItemTransfer $itemTransfer,
        OrderTransfer $orderTransfer
    ): PayoutAmountCalculatorStrategyInterface {
        foreach ($this->payoutAmountCalculatorStrategies as $payoutAmountCalculatorStrategy) {
            if ($payoutAmountCalculatorStrategy->isApplicable($itemTransfer, $orderTransfer)) {
                return $payoutAmountCalculatorStrategy;
            }
        }

        throw new PayoutAmountCalculatorStrategyNotFoundException(
            sprintf(
                'Payout amount calculator strategy not found for item with SKU %s',
                $itemTransfer->getSkuOrFail(),
            ),
        );
    }
}
