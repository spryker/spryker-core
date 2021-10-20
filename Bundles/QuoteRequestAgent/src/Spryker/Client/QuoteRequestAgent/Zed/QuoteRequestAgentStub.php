<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent\Zed;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToZedRequestClientInterface;

class QuoteRequestAgentStub implements QuoteRequestAgentStubInterface
{
    /**
     * @var \Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\QuoteRequestAgent\Dependency\Client\QuoteRequestAgentToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(QuoteRequestAgentToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\QuoteRequestAgent\Communication\Controller\GatewayController::createQuoteRequestAction()
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/create-quote-request',
            $quoteRequestTransfer,
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\QuoteRequestAgent\Communication\Controller\GatewayController::updateQuoteRequestAction()
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/update-quote-request',
            $quoteRequestTransfer,
        );

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        return $quoteRequestResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\QuoteRequestAgent\Communication\Controller\GatewayController::cancelQuoteRequestAction()
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/cancel-quote-request',
            $quoteRequestFilterTransfer,
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\QuoteRequestAgent\Communication\Controller\GatewayController::reviseQuoteRequestAction()
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/revise-quote-request',
            $quoteRequestFilterTransfer,
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\QuoteRequestAgent\Communication\Controller\GatewayController::sendQuoteRequestToCustomerAction()
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/send-quote-request-to-customer',
            $quoteRequestFilterTransfer,
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\QuoteRequestAgent\Communication\Controller\GatewayController::getQuoteRequestOverviewCollectionAction()
     *
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(
        QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
    ): QuoteRequestOverviewCollectionTransfer {
        /** @var \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer $quoteRequestOverviewCollectionTransfer */
        $quoteRequestOverviewCollectionTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/get-quote-request-overview-collection',
            $quoteRequestOverviewFilterTransfer,
        );

        return $quoteRequestOverviewCollectionTransfer;
    }
}
