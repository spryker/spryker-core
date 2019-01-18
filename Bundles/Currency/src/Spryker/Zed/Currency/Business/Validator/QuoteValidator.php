<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Store\Business\StoreFacade;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_mode_is_missing';
    protected const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_mode_is_incorrect';
    protected const GLOSSARY_KEY_ISO_CODE = '{{iso_code}}';

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacade
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacade $storeFacade
     */
    public function __construct(StoreFacade $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $currencyTransfer = $quoteTransfer->getCurrency();
        $currencyCode = $currencyTransfer->getCode();

        if (!$currencyTransfer || !$currencyCode) {
            return $this->createValidationError(static::MESSAGE_CURRENCY_DATA_IS_MISSING);
        }

        if (!$quoteTransfer->getStore()) {
            return $this->createValidationError();
        }

        $storeTransfer = $this->storeFacade->getStoreByName($quoteTransfer->getStore()->getName());

        if ($storeTransfer && array_search($currencyCode, $storeTransfer->getAvailableCurrencyIsoCodes()) === false) {
            return $this->createValidationError(
                static::MESSAGE_CURRENCY_DATA_IS_INCORRECT,
                [static::GLOSSARY_KEY_ISO_CODE => $currencyCode]
            );
        }

        return $this->createValidationError();
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function createValidationError(string $message = '', array $parameters = []): QuoteValidationResponseTransfer
    {
        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsSuccess(true);

        if (!$message) {
            return $quoteValidationResponseTransfer;
        }

        $error = (new MessageTransfer())->setValue($message)
            ->setParameters($parameters);

        return $quoteValidationResponseTransfer->setIsSuccess(false)
            ->addErrors($error);
    }
}
