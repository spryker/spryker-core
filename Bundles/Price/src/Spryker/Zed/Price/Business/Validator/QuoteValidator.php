<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Validator;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
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
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $priceMode = $quoteTransfer->getPriceMode();
        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(false);

        if (!$priceMode) {
            return $quoteValidationResponseTransfer->setIsSuccessful(true);
        }

        $availablePriceModes = $this->priceConfig->getPriceModes();

        if (!isset($availablePriceModes[$priceMode])) {
            return $this->addValidationError(
                $quoteValidationResponseTransfer,
                static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT,
                [static::GLOSSARY_KEY_PRICE_MODE => $priceMode]
            );
        }

        return $quoteValidationResponseTransfer->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     * @param string $errorMessage
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function addValidationError(
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer,
        string $errorMessage,
        array $parameters = []
    ): QuoteValidationResponseTransfer {
        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($errorMessage)
            ->setParameters($parameters);

        return $quoteValidationResponseTransfer
            ->addErrors($quoteErrorTransfer)
            ->setIsSuccessful(false);
    }
}
