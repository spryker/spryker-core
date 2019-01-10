<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuotePriceConnector\Communication\Plugin;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatePluginInterface;

/**
 * @method \Spryker\Zed\QuotePriceConnector\Communication\QuotePriceCommunicationFactory getFactory()
 */
class QuotePriceModeValidatePlugin extends AbstractPlugin implements QuoteValidatePluginInterface
{
    protected const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';
    protected const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';

    /**
     * {@inheritdoc}
     * - Validate quote price mode before saving.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(
        QuoteTransfer $quoteTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        $priceMode = $quoteTransfer->getPriceMode();

        if (!$priceMode) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_PRICE_MODE_DATA_IS_MISSING);
        }

        $availablePiceModes = $this->getFactory()->getPriceFacade()->getPriceModes();

        if (!isset($availablePiceModes[$priceMode])) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT, $priceMode);
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     * @param string $message
     * @param mixed ...$replacements
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function addValidationError(
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer,
        string $message,
        ...$replacements
    ): QuoteValidationResponseTransfer {
        if ($replacements) {
            $message = vsprintf($message, $replacements);
        }

        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($message);

        return $quoteValidationResponseTransfer
            ->addErrors($quoteErrorTransfer)
            ->setIsSuccess(false);
    }
}
