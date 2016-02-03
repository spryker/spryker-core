<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Checkout\Business\Fixture;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;

class MockPostHook implements CheckoutPostSaveHookInterface
{

    /**
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    private $checkoutResponse;

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     */
    public function __construct(CheckoutResponseTransfer $checkoutResponse)
    {
        $this->checkoutResponse = $checkoutResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $checkoutResponse->fromArray($this->checkoutResponse->toArray(true), true);
    }

}
