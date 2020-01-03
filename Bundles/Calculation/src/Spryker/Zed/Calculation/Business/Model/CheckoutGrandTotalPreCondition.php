<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\Executor\QuoteCalculatorExecutorInterface;
use Spryker\Zed\Calculation\CalculationConfig;

class CheckoutGrandTotalPreCondition implements CheckoutGrandTotalPreConditionInterface
{
    /**
     * @var \Spryker\Zed\Calculation\Business\Model\Executor\QuoteCalculatorExecutorInterface
     */
    protected $stackExecutor;

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\Executor\QuoteCalculatorExecutorInterface $stackExecutor
     */
    public function __construct(QuoteCalculatorExecutorInterface $stackExecutor)
    {
        $this->stackExecutor = $stackExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateCheckoutGrandTotal(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $totalsBefore = $quoteTransfer->getTotals()->getGrandTotal();
        $this->stackExecutor->recalculate($quoteTransfer);
        $totalsAfter = $quoteTransfer->getTotals()->getGrandTotal();

        if ($totalsBefore !== $totalsAfter) {
            $error = $this->createCheckoutErrorTransfer();
            $error
                ->setErrorCode(CalculationConfig::ERROR_CODE_CART_AMOUNT_DIFFERENT)
                ->setMessage('Checkout grand total changed.');

            $checkoutResponseTransfer->addError($error);

            return false;
        }

        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }
}
