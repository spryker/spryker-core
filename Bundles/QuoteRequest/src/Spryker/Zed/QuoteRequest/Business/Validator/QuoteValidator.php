<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class QuoteValidator implements QuoteValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_IS_NOT_APPLICABLE = 'quote_request.validation.error.is_not_applicable';

    /**
     * @var \Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestPreCreateCheckPluginInterface[]
     */
    protected $quoteRequestPreCreateCheckPlugins;

    /**
     * @param \Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestPreCreateCheckPluginInterface[] $quoteRequestPreCreateCheckPlugins
     */
    public function __construct(array $quoteRequestPreCreateCheckPlugins)
    {
        $this->quoteRequestPreCreateCheckPlugins = $quoteRequestPreCreateCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function isQuoteApplicableForQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        if (!$this->checkIsQuoteApplicableForQuoteRequest($quoteRequestTransfer)) {
            $messageTransfer = (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_QUOTE_REQUEST_IS_NOT_APPLICABLE);

            return (new QuoteRequestResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage($messageTransfer);
        }

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function checkIsQuoteApplicableForQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        $quoteTransfer = $quoteRequestTransfer->getLatestVersionOrFail()->getQuoteOrFail();

        if (!$quoteTransfer->getCustomer() || !$quoteTransfer->getCustomer()->getCompanyUserTransfer()) {
            return false;
        }

        if ($quoteTransfer->getQuoteRequestVersionReference() || $quoteTransfer->getQuoteRequestReference()) {
            return false;
        }

        if (!$this->canWriteToQuote($quoteTransfer)) {
            return false;
        }

        return $this->executeQuoteRequestQuoteCheckPlugins($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function canWriteToQuote(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->isOwnerOfCart($quoteTransfer) || $this->canCompanyUserWriteToQuote($quoteTransfer)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function executeQuoteRequestQuoteCheckPlugins(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        foreach ($this->quoteRequestPreCreateCheckPlugins as $quoteRequestQuoteCheckPlugin) {
            if (!$quoteRequestQuoteCheckPlugin->isApplicable($quoteRequestTransfer)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isOwnerOfCart(QuoteTransfer $quoteTransfer): bool
    {
        return ($quoteTransfer->getCustomerReference() === $quoteTransfer->getCustomer()->getCustomerReference());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function canCompanyUserWriteToQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getCompanyUserId()
            && $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getCompanyUserId(), $quoteTransfer->getIdQuote());
    }
}
