<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Business\Model;

use Generated\Shared\DiscountCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\DiscountCheckoutConnector\DiscountInterface;
use Generated\Shared\DiscountCheckoutConnector\OrderInterface;

class DiscountOrderHydrator implements DiscountOrderHydratorInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrder(OrderInterface $orderTransfer, CheckoutRequestInterface $request)
    {
        $this->addCartDiscountsToOrder($orderTransfer, $request);
        $this->addCartItemsDiscountsToOrder($orderTransfer, $request);

        $this->addDiscountsToOrder($orderTransfer, $request->getDiscounts());
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutRequestInterface $request
     */
    protected function addCartDiscountsToOrder(OrderInterface $orderTransfer, CheckoutRequestInterface $request)
    {
        $this->addDiscountsToOrder($orderTransfer, $request->getCart()->getDiscounts());
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutRequestInterface $request
     */
    protected function addCartItemsDiscountsToOrder(OrderInterface $orderTransfer, CheckoutRequestInterface $request)
    {
        $itemCollection = $request->getCart()->getItems();
        foreach ($itemCollection as $itemTransfer) {
            $discountCollection = $itemTransfer->getDiscounts();
            $this->addDiscountsToOrder($orderTransfer, $discountCollection);
        }
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param DiscountInterface[] $discountCollection
     */
    protected function addDiscountsToOrder(OrderInterface $orderTransfer, $discountCollection)
    {
        foreach ($discountCollection as $discountTransfer) {
            $orderTransfer->addDiscount($discountTransfer);
        }
    }

}
