<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Checkout\Business\Fixture;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

class ResponseManipulatorPreCondition implements CheckoutPreConditionInterface
{

    /**
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected $checkoutResponse;

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     */
    public function __construct(CheckoutResponseTransfer $checkoutResponse)
    {
        $this->checkoutResponse = $checkoutResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $checkoutResponseTransfer->fromArray($this->checkoutResponse->toArray(true), true);
    }

}
