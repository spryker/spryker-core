<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Zed\Checkout\Business\Calculation\CalculableContainer;

/**
 * @method \Spryker\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorBusinessFactory getFactory()
 */
class CalculationCheckoutConnectorFacade extends AbstractFacade implements CalculationCheckoutConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function checkCartAmountCorrect(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response)
    {
        $cart = $request->getCart();
        $calculationFacade = $this->getFactory()->getCalculationFacade();

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
     * @param \Spryker\Zed\Checkout\Business\Calculation\CalculableContainer $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(CalculableContainer $calculableContainer)
    {
        return $this->getFactory()->getCalculationFacade()->recalculate($calculableContainer);
    }

}
