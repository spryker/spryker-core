<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/create-quote-request',
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
            '/quote-request-agent/gateway/update-quote-request',
            $quoteRequestTransfer
        );

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/cancel-quote-request',
            $quoteRequestCriteriaTransfer
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/revise-quote-request',
            $quoteRequestCriteriaTransfer
        );

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer */
        $quoteRequestResponseTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/send-quote-request-to-customer',
            $quoteRequestCriteriaTransfer
        );

        return $quoteRequestResponseTransfer;
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
            '/quote-request-agent/gateway/get-quote-request-overview-collection',
            $quoteRequestOverviewFilterTransfer
        );

        return $quoteRequestOverviewCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByQuery(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer */
        $companyUserCollectionTransfer = $this->zedRequestClient->call(
            '/quote-request-agent/gateway/get-company-user-collection-by-query',
            $companyUserCriteriaTransfer
        );

        return $companyUserCollectionTransfer;
    }
}
