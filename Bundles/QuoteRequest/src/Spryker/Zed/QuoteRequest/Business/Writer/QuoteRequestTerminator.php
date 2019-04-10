<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Writer;

use DateTime;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface;
use Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestStatusInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestTerminator implements QuoteRequestTerminatorInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface
     */
    protected $quoteRequestReader;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestStatusInterface
     */
    protected $quoteRequestStatus;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     * @param \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface $quoteRequestReader
     * @param \Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestStatusInterface $quoteRequestStatus
     */
    public function __construct(
        QuoteRequestEntityManagerInterface $quoteRequestEntityManager,
        QuoteRequestRepositoryInterface $quoteRequestRepository,
        QuoteRequestReaderInterface $quoteRequestReader,
        QuoteRequestStatusInterface $quoteRequestStatus
    ) {
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
        $this->quoteRequestRepository = $quoteRequestRepository;
        $this->quoteRequestReader = $quoteRequestReader;
        $this->quoteRequestStatus = $quoteRequestStatus;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestCriteriaTransfer->requireIdCompanyUser();

        $quoteRequestTransfer = $this->quoteRequestReader->findQuoteRequest($quoteRequestCriteriaTransfer);

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);
        }

        if (!$this->quoteRequestStatus->isQuoteRequestCancelable($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_CANCELED);
        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @return void
     */
    public function closeOutdatedQuoteRequests(): void
    {
        $this->quoteRequestEntityManager->closeOutdatedQuoteRequests(new DateTime());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function closeQuoteRequest(QuoteTransfer $quoteTransfer): void
    {
        if (!$quoteTransfer->getQuoteRequestVersionReference()) {
            return;
        }

        $quoteRequestTransfer = $this->quoteRequestRepository
            ->findQuoteRequestByVersionReference($quoteTransfer->getQuoteRequestVersionReference());

        if (!$quoteRequestTransfer) {
            return;
        }

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_CLOSED);
        $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteRequestResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
