<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Checkout\CheckoutConstants;

class CheckoutGrandTotalPrecondition
{
    /**
     * @var StackExecutor
     */
    protected $stackExecutor;

    /**
     * CheckoutGrandTotalPrecondition constructor.
     */
    public function __construct(StackExecutor $stackExecutor)
    {
        $this->stackExecutor = $stackExecutor;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
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
                ->setErrorCode(CheckoutConstants::ERROR_CODE_CART_AMOUNT_DIFFERENT)
                ->setMessage('Checkout grand total changed.');

            $checkoutResponseTransfer->addError($error);
        }
    }

    /**
     * @return CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer()
    {
        return new CheckoutErrorTransfer();
    }
}
