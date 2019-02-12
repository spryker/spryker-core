<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Validator;

use Generated\Shared\Transfer\ErrorMessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreInterface;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_mode_is_missing';
    protected const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_mode_is_incorrect';
    protected const GLOSSARY_KEY_ISO_CODE = '{{iso_code}}';

    /**
     * @var \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreInterface $storeFacade
     */
    public function __construct(CurrencyToStoreInterface $storeFacade)
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
        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsSuccess(false);

        if (!$currencyTransfer || !$currencyTransfer->getCode()) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_CURRENCY_DATA_IS_MISSING);
        }

        if (!$quoteTransfer->getStore()) {
            return $quoteValidationResponseTransfer;
        }

        $currencyCode = $currencyTransfer->getCode();
        $storeTransfer = $this->storeFacade->getStoreByName($quoteTransfer->getStore()->getName());

        if ($storeTransfer && array_search($currencyCode, $storeTransfer->getAvailableCurrencyIsoCodes()) === false) {
            return $this->addValidationError(
                $quoteValidationResponseTransfer,
                static::MESSAGE_CURRENCY_DATA_IS_INCORRECT,
                [static::GLOSSARY_KEY_ISO_CODE => $currencyCode]
            );
        }

        return $quoteValidationResponseTransfer->setIsSuccess(true);
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
        $errorTransfer = (new ErrorMessageTransfer())->setValue($errorMessage)
            ->setParameters($parameters);

        return $quoteValidationResponseTransfer->addErrors($errorTransfer);
    }
}
