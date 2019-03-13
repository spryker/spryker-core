<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest;

use Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStubInterface;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AgentQuoteRequest\AgentQuoteRequestFactory getFactory()
 */
class AgentQuoteRequestClient extends AbstractClient implements AgentQuoteRequestClientInterface
{
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
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelByReference(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->cancelByReference($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function setQuoteRequestEditable(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getZedStub()->setQuoteRequestEditable($quoteRequestFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $customerQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer
     */
    public function findCompanyUsersByQuery(CompanyUserQueryTransfer $customerQueryTransfer): CompanyUserAutocompleteResponseTransfer
    {
        return $this->getZedStub()->findCompanyUsersByQuery($customerQueryTransfer);
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
    public function convertQuoteRequestToQuoteInProgress(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteRequestConverter()
            ->convertQuoteRequestToQuoteInProgress($quoteRequestTransfer);
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
            ->createQuoteRequestChecker()
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
    public function isQuoteRequestCanStartEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->getFactory()
            ->createQuoteRequestChecker()
            ->isQuoteRequestCanStartEditable($quoteRequestTransfer);
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
            ->createQuoteRequestChecker()
            ->isQuoteRequestEditable($quoteRequestTransfer);
    }

    /**
     * @return \Spryker\Client\AgentQuoteRequest\Zed\AgentQuoteRequestStubInterface
     */
    protected function getZedStub(): AgentQuoteRequestStubInterface
    {
        return $this->getFactory()->createAgentQuoteRequestStub();
    }
}
