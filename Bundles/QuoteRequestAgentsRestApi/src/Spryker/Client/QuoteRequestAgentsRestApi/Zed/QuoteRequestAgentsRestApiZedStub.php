<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgentsRestApi\Zed;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToZedRequestClientInterface;

class QuoteRequestAgentsRestApiZedStub implements QuoteRequestAgentsRestApiZedStubInterface
{
    /**
     * @var \Spryker\Client\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(QuoteRequestAgentsRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\QuoteRequestAgentsRestApi\Communication\Controller\GatewayController::updateQuoteRequestAction()
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call('/quote-request-agents-rest-api/gateway/update-quote-request', $quoteRequestTransfer);

        return $quoteRequestResponseTransfer;
    }
}
