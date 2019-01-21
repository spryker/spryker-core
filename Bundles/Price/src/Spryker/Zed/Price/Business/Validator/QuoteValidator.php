<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
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
            ->setIsSuccess(true);

        if (!$priceMode) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_PRICE_MODE_DATA_IS_MISSING);
        }

        $availablePiceModes = $this->priceConfig->getPriceModes();

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
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function addValidationError(
        $quoteValidationResponseTransfer,
        string $message,
        array $parameters = []
    ): QuoteValidationResponseTransfer {
        $error = (new MessageTransfer())->setValue($message)
            ->setParameters($parameters);

        return $quoteValidationResponseTransfer->setIsSuccess(false)
            ->addErrors($error);
    }
}
