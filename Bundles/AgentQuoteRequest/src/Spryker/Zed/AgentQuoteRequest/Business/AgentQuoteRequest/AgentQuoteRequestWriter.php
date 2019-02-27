<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\AgentQuoteRequest\AgentQuoteRequestConfig as SharedAgentQuoteRequestConfig;
use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig;
use Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface;

class AgentQuoteRequestWriter implements AgentQuoteRequestWriterInterface
{
    protected const ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';
    protected const ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface
     */
    protected $quoteRequestFacade;

    /**
     * @var \Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig
     */
    protected $agentQuoteRequestConfig;

    /**
     * @param \Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface $quoteRequestFacade
     * @param \Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig $agentQuoteRequestConfig
     */
    public function __construct(
        AgentQuoteRequestToQuoteRequestInterface $quoteRequestFacade,
        AgentQuoteRequestConfig $agentQuoteRequestConfig
    ) {
        $this->quoteRequestFacade = $quoteRequestFacade;
        $this->agentQuoteRequestConfig = $agentQuoteRequestConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelByReference(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestFilterTransfer->requireQuoteRequestReference();

        $quoteRequestTransfer = $this->findQuoteRequest($quoteRequestFilterTransfer);
        $quoteRequestResponseTransfer = new QuoteRequestResponseTransfer();

        if (!$quoteRequestTransfer) {
            return $quoteRequestResponseTransfer
                ->setIsSuccess(false)
                ->addError(static::ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS);
        }

        if (!$this->isQuoteRequestCancelable($quoteRequestTransfer)) {
            return $quoteRequestResponseTransfer
                ->setIsSuccess(false)
                ->addError(static::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestTransfer->setStatus(SharedAgentQuoteRequestConfig::STATUS_CANCELED);

        return $this->quoteRequestFacade->update($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function setQuoteRequestEditable(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestFilterTransfer->requireQuoteRequestReference();

        $quoteRequestTransfer = $this->findQuoteRequest($quoteRequestFilterTransfer);
        $quoteRequestResponseTransfer = new QuoteRequestResponseTransfer();

        if (!$quoteRequestTransfer) {
            return $quoteRequestResponseTransfer
                ->setIsSuccess(false)
                ->addError(static::ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS);
        }

        if (!$this->isQuoteRequestEditable($quoteRequestTransfer)) {
            return $quoteRequestResponseTransfer
                ->setIsSuccess(false)
                ->addError(static::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestTransfer->requireLatestVersion()
            ->getLatestVersion()
            ->requireQuote();

        $quoteRequestTransfer->setStatus(SharedAgentQuoteRequestConfig::STATUS_IN_PROGRESS);
        $quoteRequestTransfer->setQuoteInProgress($quoteRequestTransfer->getLatestVersion()->getQuote());

        return $this->quoteRequestFacade->update($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestTransfers = $this->quoteRequestFacade
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return in_array($quoteRequestTransfer->getStatus(), $this->agentQuoteRequestConfig->getCancelableStatuses());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $quoteRequestTransfer->getStatus() === SharedAgentQuoteRequestConfig::STATUS_WAITING;
    }
}
