<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Checkout;

use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payone\Business\PayoneCommunicationFactory;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;

/**
 * @method PayoneCommunicationFactory getFactory()
 */
class CheckoutOrderHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $orderTransfer->setPayonePayment($checkoutRequest->getPayonePayment());
    }

}
