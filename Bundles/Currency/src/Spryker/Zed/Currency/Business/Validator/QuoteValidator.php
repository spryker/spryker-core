<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): MessageTransfer
    {
        $currencyTransfer = $quoteTransfer->getCurrency();
        $storeTransfer = $this->storeFacade->getStoreByName($quoteTransfer->getStore()->getName());
        $currencyCode = $currencyTransfer->getCode();

        if (!$currencyTransfer || !$currencyCode) {
            return $this->createValidationError(static::MESSAGE_CURRENCY_DATA_IS_MISSING);
        }

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
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createValidationError(string $message = '', array $parameters = []): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message)
            ->setParameters($parameters);
    }
}
