<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use DateTime;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestChecker implements QuoteRequestCheckerInterface
{
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND = 'quote_request.checkout.validation.error.version_not_found';
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_NOT_FOUND = 'quote_request.checkout.validation.error.not_found';
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION = 'quote_request.checkout.validation.error.wrong_version';
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.validation.error.wrong_valid_until';

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     */
    public function __construct(QuoteRequestRepositoryInterface $quoteRequestRepository)
    {
        $this->quoteRequestRepository = $quoteRequestRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function checkValidUntil(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        if (!$quoteTransfer->getQuoteRequestVersionReference()) {
            return (new QuoteValidationResponseTransfer())->setIsSuccessful(true);
        }

        $quoteRequestVersionTransfer = $this->findQuoteRequestVersion($quoteTransfer->getQuoteRequestVersionReference());

        if (!$quoteRequestVersionTransfer) {
            return $this->getErrorQuoteValidationResponse(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND);
        }

        $quoteRequestTransfer = $this->findQuoteRequest($quoteRequestVersionTransfer);

        if (!$quoteRequestTransfer) {
            return $this->getErrorQuoteValidationResponse(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_NOT_FOUND);
        }

        return $this->isQuoteRequestValid($quoteRequestTransfer, $quoteRequestVersionTransfer);
    }

    /**
     * @param string $quoteRequestVersionReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    protected function findQuoteRequestVersion(string $quoteRequestVersionReference): ?QuoteRequestVersionTransfer
    {
        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
            ->setQuoteRequestVersionReference($quoteRequestVersionReference);

        $quoteRequestVersionTransfers = $this->quoteRequestRepository
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        return array_shift($quoteRequestVersionTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequest(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestVersionTransfer->requireQuoteRequest()
            ->getQuoteRequest()
            ->requireQuoteRequestReference();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestVersionTransfer->getQuoteRequest()->getQuoteRequestReference());

        $quoteRequestTransfers = $this->quoteRequestRepository
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function isQuoteRequestValid(
        QuoteRequestTransfer $quoteRequestTransfer,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): QuoteValidationResponseTransfer {
        if ($quoteRequestTransfer->getStatus() !== SharedQuoteRequestConfig::STATUS_READY) {
            return $this->getErrorQuoteValidationResponse(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS);
        }

        if ($quoteRequestTransfer->getLatestVersion()->getIdQuoteRequestVersion() !== $quoteRequestVersionTransfer->getIdQuoteRequestVersion()) {
            return $this->getErrorQuoteValidationResponse(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION);
        }

        if (!$quoteRequestTransfer->getValidUntil()
            || (new DateTime($quoteRequestTransfer->getValidUntil()) < new DateTime('now'))) {
            return $this->getErrorQuoteValidationResponse(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL);
        }

        return (new QuoteValidationResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function getErrorQuoteValidationResponse(string $message): QuoteValidationResponseTransfer
    {
        return (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setValue($message));
    }
}
