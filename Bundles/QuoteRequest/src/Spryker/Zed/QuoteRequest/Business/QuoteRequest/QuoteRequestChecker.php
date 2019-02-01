<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use DateTime;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestChecker implements QuoteRequestCheckerInterface
{
    public const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_NOT_FOUND = 'quote_request.checkout.validation.error.not_found';
    public const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';
    public const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION = 'quote_request.checkout.validation.error.wrong_version';
    public const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.validation.error.wrong_valid_until';

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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkValidUntil(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if (!$quoteTransfer->getQuoteRequestVersionReference()) {
            return true;
        }

        $quoteRequestVersionTransfer = $this->findQuoteRequestVersion($quoteTransfer->getQuoteRequestVersionReference());

        if (!$quoteRequestVersionTransfer) {
            $checkoutResponseTransfer->addError(
                (new CheckoutErrorTransfer())->setMessage(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_NOT_FOUND)
            );
            $checkoutResponseTransfer->setIsSuccess(false);

            return false;
        }

        $quoteRequestTransfer = $this->getQuoteRequest($quoteRequestVersionTransfer);

        if ($this->isQuoteRequestValid($quoteRequestTransfer, $quoteRequestVersionTransfer, $checkoutResponseTransfer)) {
            return true;
        }

        $checkoutResponseTransfer->setIsSuccess(false);

        return false;
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

        $quoteRequestVersions = $this->quoteRequestRepository
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        $quoteRequestVersionTransfer = array_shift($quoteRequestVersions);

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getQuoteRequest(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): QuoteRequestTransfer
    {
        $quoteRequestVersionTransfer->requireQuoteRequest()
            ->getQuoteRequest()
            ->requireQuoteRequestReference();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestVersionTransfer->getQuoteRequest()->getQuoteRequestReference());

        $quoteRequests = $this->quoteRequestRepository
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        $quoteRequestTransfer = array_shift($quoteRequests);

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestValid(
        QuoteRequestTransfer $quoteRequestTransfer,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        if ($quoteRequestTransfer->getStatus() !== SharedQuoteRequestConfig::STATUS_READY) {
            $checkoutResponseTransfer->addError(
                (new CheckoutErrorTransfer())->setMessage(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS)
            );

            return false;
        }

        if ($quoteRequestTransfer->getLatestVersion()->getIdQuoteRequestVersion() !== $quoteRequestVersionTransfer->getIdQuoteRequestVersion()) {
            $checkoutResponseTransfer->addError(
                (new CheckoutErrorTransfer())->setMessage(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION)
            );

            return false;
        }

        if (!$quoteRequestTransfer->getValidUntil()
            && (new DateTime($quoteRequestTransfer->getValidUntil()) > new DateTime('now'))) {
            $checkoutResponseTransfer->addError(
                (new CheckoutErrorTransfer())->setMessage(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VALID_UNTIL)
            );

            return false;
        }

        return true;
    }
}
