<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Zed;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/create-quote-request',
            $quoteRequestTransfer
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/update-quote-request',
            $quoteRequestTransfer
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/revise-quote-request',
            $quoteRequestFilterTransfer
        );

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/cancel-quote-request',
            $quoteRequestFilterTransfer
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToUser(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/send-quote-request-to-user',
            $quoteRequestFilterTransfer
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer */
        $quoteRequestCollectionTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/get-quote-request-collection-by-filter',
            $quoteRequestFilterTransfer
        );

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer): QuoteRequestVersionCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer $quoteRequestVersionCollectionTransfer */
        $quoteRequestVersionCollectionTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/get-quote-request-version-collection-by-filter',
            $quoteRequestVersionFilterTransfer
        );

        return $quoteRequestVersionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function getQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request/gateway/get-quote-request',
            $quoteRequestFilterTransfer
        );

        return $quoteRequestResponseTransfer;
    }
}
