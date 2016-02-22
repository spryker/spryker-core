<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @param \Generated\Shared\Transfer\OrderTransfer $order
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     *
     * @throws \Spryker\Zed\OmsCheckoutConnector\Business\Exception\NoStatemachineProcessException
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
