<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\OmsCheckoutConnector\Business;

use Generated\Shared\OmsCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\OmsCheckoutConnector\OrderInterface;
use SprykerFeature\Zed\OmsCheckoutConnector\Business\Exception\NoStatemachineProcessException;

class OmsOrderHydrator implements OmsOrderHydratorInterface
{

    //TODO provide a 'real' implementation for it!
    const PAYMENT_METHOD_CREDITCARD = 'creditcard';
    const PAYMENT_METHOD_VORKASSE = 'prepay';

    /**
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     *
     * @throws NoStatemachineProcessException
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
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
