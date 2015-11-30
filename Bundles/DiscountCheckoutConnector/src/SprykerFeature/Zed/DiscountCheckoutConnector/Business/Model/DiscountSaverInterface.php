<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface DiscountSaverInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     */
    public function saveDiscounts(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

}
