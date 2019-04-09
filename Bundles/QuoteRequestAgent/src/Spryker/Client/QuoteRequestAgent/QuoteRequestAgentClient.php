<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\QuoteRequestAgent\Zed\QuoteRequestAgentStubInterface;

/**
 * @method \Spryker\Client\QuoteRequestAgent\QuoteRequestAgentFactory getFactory()
 */
class QuoteRequestAgentClient extends AbstractClient implements QuoteRequestAgentClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->reviseQuoteRequest($quoteRequestCriteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->cancelQuoteRequest($quoteRequestCriteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    public function getQuoteRequestOverviewCollection(QuoteRequestOverviewFilterTransfer $quoteRequestOverviewFilterTransfer): QuoteRequestOverviewCollectionTransfer
    {
        return $this->getZedStub()->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequestByReference(string $quoteRequestReference): ?QuoteRequestTransfer
    {
        return $this->getFactory()
            ->createQuoteRequestAgentReader()
            ->findQuoteRequestByReference($quoteRequestReference);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteRequestAgentConverter()
            ->convertQuoteRequestToQuote($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByQuery(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer
    {
        return $this->getZedStub()->getCompanyUserCollectionByQuery($companyUserCriteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteRequestAgentStatus()
            ->isQuoteRequestCancelable($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestRevisable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteRequestAgentStatus()
            ->isQuoteRequestRevisable($quoteRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteRequestAgentStatus()
            ->isQuoteRequestEditable($quoteRequestTransfer);
    }

    /**
     * @return \Spryker\Client\QuoteRequestAgent\Zed\QuoteRequestAgentStubInterface
     */
    protected function getZedStub(): QuoteRequestAgentStubInterface
    {
        return $this->getFactory()->createQuoteRequestAgentStub();
    }
}
