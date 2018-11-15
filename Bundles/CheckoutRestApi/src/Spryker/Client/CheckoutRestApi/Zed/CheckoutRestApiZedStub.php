<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutRestApi\Zed;

use Generated\Shared\Transfer\CheckoutDataResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface;

class CheckoutRestApiZedStub implements CheckoutRestApiZedStubInterface
{
    /**
     * @var \Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CheckoutRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutDataResponseTransfer
     */
    public function getCheckoutData(QuoteTransfer $quoteTransfer): CheckoutDataResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataResponseTransfer $checkoutDataResponseTransfer */
        $checkoutDataResponseTransfer = $this->zedRequestClient->call('/checkout-rest-api/gateway/get-checkout-data', $quoteTransfer);

        return $checkoutDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer */
        $checkoutResponseTransfer = $this->zedRequestClient->call('/checkout-rest-api/gateway/place-order', $quoteTransfer);

        return $checkoutResponseTransfer;
    }
}
