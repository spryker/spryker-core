<?php

namespace SprykerFeature\Zed\CalculationCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Checkout\CheckoutConfig;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Checkout\Business\Calculation\CalculableContainer;

/**
 * @method CalculationCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CalculationCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param CheckoutRequestTransfer $request
     * @param CheckoutResponseTransfer $response
     */
    public function checkCartAmountCorrect(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response)
    {
        $cart = $request->getCart();
        $calculationFacade = $this->getDependencyContainer()->getCalculationFacade();

        $totalsBefore = $cart->getTotals()->getGrandTotalWithDiscounts();
        $calculationFacade->recalculate($cart);
        $totalsAfter = $cart->getTotals()->getGrandTotalWithDiscounts();

        if ($totalsBefore !== $totalsAfter) {
            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode(CheckoutConfig::ERROR_CODE_CART_AMOUNT_DIFFERENT)
                ->setMessage('Cart-Werte stimmen nicht überein')
            ;

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
        return $this->getDependencyContainer()->getCalculationFacade()->recalculate($calculableContainer);
    }

}
