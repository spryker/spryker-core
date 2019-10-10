<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout\Zed;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CheckoutStub implements CheckoutStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @uses \Spryker\Zed\Checkout\Communication\Controller\GatewayController::placeOrderAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer */
        $checkoutResponseTransfer = $this->zedStub->call('/checkout/gateway/place-order', $quoteTransfer);

        return $checkoutResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\Checkout\Communication\Controller\GatewayController::isPlaceableOrderAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function isPlaceableOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer */
        $checkoutResponseTransfer = $this->zedStub->call('/checkout/gateway/is-placeable-order', $quoteTransfer);

        return $checkoutResponseTransfer;
    }
}
