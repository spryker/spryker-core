<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;

interface DiscountSaverInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutRequestInterface $request
     */
    public function saveDiscounts(OrderInterface $orderTransfer, CheckoutRequestInterface $request);

}
