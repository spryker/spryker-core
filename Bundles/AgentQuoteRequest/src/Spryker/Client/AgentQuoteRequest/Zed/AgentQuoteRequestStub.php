<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest\Zed;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToZedRequestClientInterface;

class AgentQuoteRequestStub implements AgentQuoteRequestStubInterface
{
    /**
     * @var \Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(AgentQuoteRequestToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestOverviewCollectionTransfer {
        /** @var \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer $quoteRequestOverviewCollectionTransfer */
        $quoteRequestOverviewCollectionTransfer = $this->zedRequestClient->call(
            '/agent-quote-request/gateway/get-quote-request-overview-collection',
            $quoteRequestOverviewFilterTransfer
        );

        return $quoteRequestOverviewCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelByReference(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/agent-quote-request/gateway/cancel-by-reference',
            $quoteRequestFilterTransfer
        );

        return $quoteRequestResponseTransfer;
    }
}
