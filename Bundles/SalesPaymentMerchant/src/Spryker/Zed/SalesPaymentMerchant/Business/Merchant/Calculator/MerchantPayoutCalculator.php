<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface;

class MerchantPayoutCalculator implements MerchantPayoutCalculatorInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME_AMOUNT = 'amount';

    /**
     * @var \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null
     */
    protected ?MerchantPayoutCalculatorPluginInterface $amountCalculatorPlugin;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface
     */
    protected MerchantPayoutCalculatorPluginInterface $amountCalculatorFallback;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface $amountCalculatorFallback
     * @param \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null $amountCalculatorPlugin
     */
    public function __construct(
        MerchantPayoutCalculatorPluginInterface $amountCalculatorFallback,
        ?MerchantPayoutCalculatorPluginInterface $amountCalculatorPlugin
    ) {
        $this->amountCalculatorFallback = $amountCalculatorFallback;
        $this->amountCalculatorPlugin = $amountCalculatorPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int
    {
        if ($this->amountCalculatorPlugin !== null) {
            return $this->amountCalculatorPlugin->calculatePayoutAmount($itemTransfer, $orderTransfer);
        }

        return $this->amountCalculatorFallback->calculatePayoutAmount($itemTransfer, $orderTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrderItemTransfer> $orderItemTransfers
     * @param list<\Generated\Shared\Transfer\OrderExpenseTransfer> $orderExpenseTransfers
     *
     * @return int
     */
    public function calculatePayoutAmountForOrder(array $orderItemTransfers, array $orderExpenseTransfers): int
    {
        $orderAmountTotal = 0;
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $orderAmountTotal += $orderItemTransfer->getAmount() ?? 0;
        }

        foreach ($orderExpenseTransfers as $orderExpenseTransfer) {
            $orderAmountTotal += $orderExpenseTransfer->getAmount() ?? 0;
        }

        return $orderAmountTotal;
    }
}
