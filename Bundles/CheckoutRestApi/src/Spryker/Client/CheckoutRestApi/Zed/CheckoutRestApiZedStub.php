<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutRestApi\Zed;

use Generated\Shared\Transfer\CheckoutDataResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
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
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutDataResponseTransfer
     */
    public function getCheckoutData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CheckoutDataResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataResponseTransfer $checkoutDataResponseTransfer */
        $checkoutDataResponseTransfer = $this->zedRequestClient->call('/checkout-rest-api/gateway/get-checkout-data', $restCheckoutRequestAttributesTransfer);

        return $checkoutDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CheckoutResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer */
        $checkoutResponseTransfer = $this->zedRequestClient->call('/checkout-rest-api/gateway/place-order', $restCheckoutRequestAttributesTransfer);

        return $checkoutResponseTransfer;
    }
}
