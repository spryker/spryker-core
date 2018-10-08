<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutRestApi\Zed;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface;

class CheckoutRestApiStub implements CheckoutRestApiStubInterface
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
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    public function getCheckoutData(QuoteTransfer $quoteTransfer): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = $this->zedRequestClient->call('/checkout-rest-api/gateway/get-checkout-data', $quoteTransfer);

        return $checkoutDataTransfer;
    }
}
