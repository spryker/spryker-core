<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

class QuoteRequestProductConfigurationValidator implements QuoteRequestProductConfigurationValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_IN_QUOTE_REQUEST_IS_INCOMPLETE = 'product_configuration.quote_request.validation.error.incomplete';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function validateQuoteRequestProductConfiguration(
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestResponseTransfer {
        if (!$this->isQuoteRequestReadyForValidation($quoteRequestTransfer)) {
            return $this->createSuccessfulResponse();
        }

        return $this->isQuoteRequestValid($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestReadyForValidation(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $quoteRequestTransfer->getLatestVersion() && $quoteRequestTransfer->getLatestVersionOrFail()->getQuote();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function isQuoteRequestValid(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        foreach ($quoteRequestTransfer->getLatestVersionOrFail()->getQuoteOrFail()->getItems() as $itemTransfer) {
            if (!$this->isProductConfigurationItem($itemTransfer)) {
                continue;
            }

            if (!$this->isProductConfigurationComplete($itemTransfer)) {
                return $this->createFailedResponse();
            }
        }

        return $this->createSuccessfulResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isProductConfigurationItem(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getProductConfigurationInstance() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isProductConfigurationComplete(ItemTransfer $itemTransfer): bool
    {
        return (bool)$itemTransfer->getProductConfigurationInstanceOrFail()->getIsComplete();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function createSuccessfulResponse(): QuoteRequestResponseTransfer
    {
        return (new QuoteRequestResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function createFailedResponse(): QuoteRequestResponseTransfer
    {
        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_IN_QUOTE_REQUEST_IS_INCOMPLETE),
            );
    }
}
