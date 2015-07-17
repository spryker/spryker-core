<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Checkout\Business\Fixture;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;

class MockPostHook implements CheckoutPostSaveHookInterface
{

    /**
     * @var CheckoutResponseInterface
     */
    private $checkoutResponse;

    /**
     * @param CheckoutResponseInterface $checkoutResponse
     */
    public function __construct(CheckoutResponseInterface $checkoutResponse)
    {
        $this->checkoutResponse = $checkoutResponse;
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function executeHook(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $checkoutResponse->fromArray($this->checkoutResponse->toArray(true));
    }

}
