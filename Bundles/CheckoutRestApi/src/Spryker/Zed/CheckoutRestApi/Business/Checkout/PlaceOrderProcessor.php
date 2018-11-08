<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface;

class PlaceOrderProcessor implements PlaceOrderProcessorInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface
     */
    protected $checkoutFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface
     */
    protected $quoteCustomerExpander;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface $quoteCustomerExpander
     */
    public function __construct(
        CheckoutRestApiToCartFacadeInterface $cartFacade,
        CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade,
        QuoteCustomerExpanderInterface $quoteCustomerExpander
    ) {
        $this->cartFacade = $cartFacade;
        $this->checkoutFacade = $checkoutFacade;
        $this->quoteCustomerExpander = $quoteCustomerExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        $this->quoteCustomerExpander->expandQuoteTransferWithCustomerTransfer($quoteTransfer);

        $quoteResponseTransfer = $this->cartFacade->validateQuote(clone $quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return (new CheckoutResponseTransfer())->fromArray($quoteResponseTransfer->toArray(), true);
        }

        $checkoutResponseTransfer = $this->checkoutFacade->placeOrder($quoteTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $checkoutResponseTransfer;
        }

        return $checkoutResponseTransfer;
    }
}
