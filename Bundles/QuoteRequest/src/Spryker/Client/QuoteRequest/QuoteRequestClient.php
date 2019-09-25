<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface;

/**
 * @method \Spryker\Client\QuoteRequest\QuoteRequestFactory getFactory()
 */
class QuoteRequestClient extends AbstractClient implements QuoteRequestClientInterface
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
        return $this->getFactory()
            ->createQuoteRequestCreator()
            ->createQuoteRequest($quoteRequestTransfer);
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
    public function sendQuoteRequestToUser(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->sendQuoteRequestToUser($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        return $this->getZedStub()->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer): QuoteRequestVersionCollectionTransfer
    {
        return $this->getZedStub()->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer);
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
    public function convertQuoteRequestToLockedQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteRequestConverter()
            ->convertQuoteRequestToLockedQuote($quoteRequestTransfer);
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
            ->createQuoteRequestConverter()
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
            ->createQuoteRequestStatus()
            ->isQuoteRequestCancelable($quoteRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApplicableForQuoteRequest(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteValidator()
            ->isQuoteApplicableForQuoteRequest($quoteTransfer);
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
            ->createQuoteRequestStatus()
            ->isQuoteRequestEditable($quoteRequestTransfer);
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
    public function isQuoteRequestReady(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteRequestStatus()
            ->isQuoteRequestReady($quoteRequestTransfer);
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
    public function getQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->getQuoteRequest($quoteRequestFilterTransfer);
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface
     */
    protected function getZedStub(): QuoteRequestStubInterface
    {
        return $this->getFactory()->createQuoteRequestStub();
    }
}
