<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class DiscountOrderHydrator implements DiscountOrderHydratorInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $request
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $request)
    {
        foreach ($request->getCart()->getCouponCodes() as $couponCode) {
            $orderTransfer->addCouponCode($couponCode);
        }
    }

}
