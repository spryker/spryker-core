<?php

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Checkout\Business\Calculation\CalculableContainer;

/**
 * @method CalculationCheckoutConnectorBusinessFactory getBusinessFactory()
 */
class CalculationCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param CheckoutRequestTransfer $request
     * @param CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function checkCartAmountCorrect(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response)
    {
        $cart = $request->getCart();
        $calculationFacade = $this->getBusinessFactory()->getCalculationFacade();

        $totalsBefore = $cart->getTotals()->getGrandTotalWithDiscounts();
        $calculationFacade->recalculate($cart);
        $totalsAfter = $cart->getTotals()->getGrandTotalWithDiscounts();

        if ($totalsBefore !== $totalsAfter) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode(CheckoutConstants::ERROR_CODE_CART_AMOUNT_DIFFERENT)
                ->setMessage('Cart values are not the same.');

            $response->addError($error);
        }
    }

    /**
     * @param CalculableContainer $calculableContainer
     *
     * @return CalculableInterface
     */
    public function recalculate(CalculableContainer $calculableContainer)
    {
        return $this->getBusinessFactory()->getCalculationFacade()->recalculate($calculableContainer);
    }

}
