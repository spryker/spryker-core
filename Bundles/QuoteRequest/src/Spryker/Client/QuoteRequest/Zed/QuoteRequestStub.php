<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Zed;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface;

class QuoteRequestStub implements QuoteRequestStubInterface
{
    /**
     * @var \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(QuoteRequestToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequestFromQuote(QuoteTransfer $quoteTransfer): QuoteRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call('/quote-request/gateway/create-quote-request-from-quote', $quoteTransfer);

        return $quoteRequestResponseTransfer;
    }
}
