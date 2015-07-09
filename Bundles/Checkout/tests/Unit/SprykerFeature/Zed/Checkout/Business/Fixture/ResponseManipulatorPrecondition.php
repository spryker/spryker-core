<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Checkout\Business\Fixture;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreconditionInterface;

class ResponseManipulatorPrecondition implements CheckoutPreconditionInterface
{

    /**
     * @var CheckoutResponseTransfer
     */
    protected $checkoutResponse;

    /**
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function __construct(CheckoutResponseTransfer $checkoutResponse)
    {
        $this->checkoutResponse = $checkoutResponse;
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $checkoutResponse->fromArray($this->checkoutResponse->toArray(true));
    }

}
