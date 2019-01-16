<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Validator;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Price\PriceConfig;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';
    protected const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';
    protected const GLOSSARY_KEY_PRICE_MODE = '{{price_mode}}';

    /**
     * @var \Spryker\Zed\Price\PriceConfig
     */
    protected $priceConfig;

    /**
     * @param \Spryker\Zed\Price\PriceConfig $priceConfig
     */
    public function __construct(PriceConfig $priceConfig)
    {
        $this->priceConfig = $priceConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteErrorTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteErrorTransfer
    {
        $priceMode = $quoteTransfer->getPriceMode();

        if (!$priceMode) {
            return $this->setValidationError(static::MESSAGE_PRICE_MODE_DATA_IS_MISSING);
        }

        $availablePiceModes = $this->priceConfig->getPriceModes();

        if (!isset($availablePiceModes[$priceMode])) {
            return $this->setValidationError(
                static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT,
                [static::GLOSSARY_KEY_PRICE_MODE => $priceMode]
            );
        }

        return $this->setValidationError();
    }

    /**
     * @param string $message
     * @param array $replacements
     *
     * @return \Generated\Shared\Transfer\QuoteErrorTransfer
     */
    protected function setValidationError(string $message = '', array $replacements = []): QuoteErrorTransfer
    {
        $quoteErrorTransfer = new QuoteErrorTransfer();

        if (!$message) {
            return $quoteErrorTransfer;
        }

        if ($replacements) {
            $message = strtr($message, $replacements);
        }

        return $quoteErrorTransfer->setMessage($message);
    }
}
