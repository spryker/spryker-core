<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface;

class PaymentStub implements PaymentStubInterface
{
    /**
     * @var \Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(PaymentToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->zedRequestClient->call('/payment/gateway/get-available-methods', $quoteTransfer, null);
    }
}
