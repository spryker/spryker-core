<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->reviseQuoteRequest($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->cancelQuoteRequest($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->sendQuoteRequestToCustomer($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
            ->createQuoteRequestReader()
            ->findQuoteRequestByReference($quoteRequestReference);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
