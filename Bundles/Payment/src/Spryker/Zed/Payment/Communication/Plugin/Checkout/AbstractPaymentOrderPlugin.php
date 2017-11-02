<?php


namespace Spryker\Zed\Payment\Communication\Plugin\Checkout;


use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Checkout place order plugin adapter.
 * From Checkout ^4 changes during the save execution in the CheckoutResponse are forbidden.
 */
class AbstractPaymentOrderPlugin extends AbstractPlugin
{
    /**
     * @param SaveOrderTransfer $saveOrderTransfer
     *
     * @return CheckoutResponseTransfer
     */
    protected function createCheckoutResponse(SaveOrderTransfer $saveOrderTransfer)
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);

        return $checkoutResponseTransfer;
    }
}