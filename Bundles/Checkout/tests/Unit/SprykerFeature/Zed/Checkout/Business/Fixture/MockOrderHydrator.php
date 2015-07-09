<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Checkout\Business\Fixture;

use Generated\Shared\Checkout\OrderInterface;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;

class MockOrderHydrator implements CheckoutOrderHydrationInterface
{

    /**
     * @var OrderInterface
     */
    private $orderTransfer;

    /**
     * @param OrderInterface $orderTransfer
     */
    public function __construct(OrderInterface $orderTransfer)
    {
        $this->orderTransfer = $orderTransfer;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $orderTransfer->fromArray($this->orderTransfer->toArray(true));
    }

}
