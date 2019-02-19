<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Business\AgentQuoteRequest;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig;
use Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface;
use Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestEntityManagerInterface;

class AgentQuoteRequestWriter implements AgentQuoteRequestWriterInterface
{
    protected const ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';
    protected const ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface
     */
    protected $quoteRequestFacade;

    /**
     * @var \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestEntityManagerInterface
     */
    protected $agentQuoteRequestEntityManager;

    /**
     * @param \Spryker\Zed\AgentQuoteRequest\Dependency\Facade\AgentQuoteRequestToQuoteRequestInterface $quoteRequestFacade
     * @param \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestEntityManagerInterface $agentQuoteRequestEntityManager
     */
    public function __construct(
        AgentQuoteRequestToQuoteRequestInterface $quoteRequestFacade,
        AgentQuoteRequestEntityManagerInterface $agentQuoteRequestEntityManager
    ) {
        $this->quoteRequestFacade = $quoteRequestFacade;
        $this->agentQuoteRequestEntityManager = $agentQuoteRequestEntityManager;
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

        if ($quoteRequestTransfer->getStatus() === AgentQuoteRequestConfig::STATUS_CANCELED) {
            return $quoteRequestResponseTransfer
                ->setIsSuccess(false)
                ->addError(static::ERROR_MESSAGE_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestTransfer->setStatus(AgentQuoteRequestConfig::STATUS_CANCELED);
        $this->agentQuoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return $quoteRequestResponseTransfer
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccess(true);
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
}
