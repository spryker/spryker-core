<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatePluginInterface;

/**
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 * @method \Spryker\Zed\Price\Communication\PriceCommunicationFactory getFactory()
 * @method \Spryker\Zed\Price\PriceConfig getConfig()
 */
class QuotePriceModeValidatePlugin extends AbstractPlugin implements QuoteValidatePluginInterface
{
    protected const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';
    protected const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';
    protected const GLOSSARY_KEY_PRICE_MODE = '{{price_mode}}';

    /**
     * {@inheritdoc}
     * - Validates if provided quote price mode is available.
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

        $availablePiceModes = $this->getFacade()->getPriceModes();

        if (!isset($availablePiceModes[$priceMode])) {
            return $this->addValidationError(
                $quoteValidationResponseTransfer,
                static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT,
                [static::GLOSSARY_KEY_PRICE_MODE => $priceMode]
            );
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     * @param string $message
     * @param array $replacements
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function addValidationError(
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer,
        string $message,
        array $replacements = []
    ): QuoteValidationResponseTransfer {
        if ($replacements) {
            $message = strtr($message, $replacements);
        }

        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($message);

        return $quoteValidationResponseTransfer
            ->addErrors($quoteErrorTransfer)
            ->setIsSuccess(false);
    }
}
