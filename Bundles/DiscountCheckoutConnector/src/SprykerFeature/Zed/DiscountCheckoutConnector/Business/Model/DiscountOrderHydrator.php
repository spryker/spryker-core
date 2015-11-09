<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;

class DiscountOrderHydrator implements DiscountOrderHydratorInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrder(OrderInterface $orderTransfer, CheckoutRequestInterface $request)
    {
        foreach ($request->getCart()->getCouponCodes() as $couponCode) {
            $orderTransfer->addCouponCode($couponCode);
        }
    }

}
