<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';
    protected const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_mode_is_missing';
    protected const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_mode_is_incorrect';
    protected const GLOSSARY_KEY_ISO_CODE = '{{iso_code}}';

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatePluginInterface[]
     */
    protected $quoteValidatePlugins;

    /**
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatePluginInterface[] $quoteValidatePlugins
     */
    public function __construct(QuoteToStoreFacadeInterface $storeFacade, array $quoteValidatePlugins)
    {
        $this->storeFacade = $storeFacade;
        $this->quoteValidatePlugins = $quoteValidatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $quoteValidationResponseTransfer = $this->initQuoteValidationResponseTransfer();
        $quoteValidationResponseTransfer = $this->validateStoreAndCurrency($quoteTransfer, $quoteValidationResponseTransfer);
        $quoteValidationResponseTransfer = $this->executeQuoteValidationPlugins($quoteTransfer, $quoteValidationResponseTransfer);

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function validateStoreAndCurrency(
        QuoteTransfer $quoteTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        $storeTransfer = $quoteTransfer->getStore();

        if (!$storeTransfer || !$storeTransfer->getName()) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_STORE_DATA_IS_MISSING);
        }

        try {
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        } catch (StoreNotFoundException $exception) {
            return $this->addValidationError($quoteValidationResponseTransfer, $exception->getMessage());
        }

        return $this->validateCurrency($quoteTransfer, $storeTransfer, $quoteValidationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function validateCurrency(
        QuoteTransfer $quoteTransfer,
        StoreTransfer $storeTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        $currencyTransfer = $quoteTransfer->getCurrency();
        $currencyCode = $currencyTransfer->getCode();

        if (!$currencyTransfer || !$currencyCode) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_CURRENCY_DATA_IS_MISSING);
        }

        if (array_search($currencyCode, $storeTransfer->getAvailableCurrencyIsoCodes()) === false) {
            return $this->addValidationError(
                $quoteValidationResponseTransfer,
                static::MESSAGE_CURRENCY_DATA_IS_INCORRECT,
                [static::GLOSSARY_KEY_ISO_CODE => $currencyCode]
            );
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function executeQuoteValidationPlugins(
        QuoteTransfer $quoteTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        if (!$this->quoteValidatePlugins) {
            return $quoteValidationResponseTransfer;
        }

        foreach ($this->quoteValidatePlugins as $quoteValidatePlugin) {
            $quoteErrorTransfer = $quoteValidatePlugin->validate($quoteTransfer);

            if ($quoteErrorTransfer->getMessage()) {
                $quoteValidationResponseTransfer->addErrors($quoteErrorTransfer)->setIsSuccess(false);
            }
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function initQuoteValidationResponseTransfer(): QuoteValidationResponseTransfer
    {
        return (new QuoteValidationResponseTransfer())
            ->setIsSuccess(true)
            ->setErrors(new ArrayObject());
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
