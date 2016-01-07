<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Zed\Calculation\Business\CalculationFacade;

class CartAmountPrecondition
{
    /**
     * @var CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @param CalculationFacade $calculationFacade
     */
    public function __construct(CalculationFacade $calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function validateCartGrandTotal(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $response)
    {
        $totalsBefore = $quoteTransfer->getTotals()->getGrandTotal();
        $this->calculationFacade->recalculate($quoteTransfer);
        $totalsAfter = $quoteTransfer->getTotals()->getGrandTotal();

        if ($totalsBefore !== $totalsAfter) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode(CheckoutConstants::ERROR_CODE_CART_AMOUNT_DIFFERENT)
                ->setMessage('Cart values are not the same.');

            $response->addError($error);
        }
    }
}
