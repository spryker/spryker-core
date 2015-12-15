<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\OmsCheckoutConnector\Business\Exception\NoStatemachineProcessException;

class OmsOrderHydrator implements OmsOrderHydratorInterface
{

    //TODO provide a 'real' implementation for it!
    const PAYMENT_METHOD_CREDITCARD = 'creditcard';
    const PAYMENT_METHOD_VORKASSE = 'prepay';

    /**
     * @param OrderTransfer $order
     * @param CheckoutRequestTransfer $request
     *
     * @throws NoStatemachineProcessException
     *
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $paymentMethod = $request->getPaymentMethod();

        switch ($paymentMethod) {
            case self::PAYMENT_METHOD_VORKASSE:
                $order->setProcess('Prepayment01');
                break;
            default:
                throw new NoStatemachineProcessException();
        }
    }

}
