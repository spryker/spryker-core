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
use Spryker\Zed\Quote\Dependency\Facade\QuoteToPriceFacadeInterface;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';
    protected const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';
    protected const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';
    protected const MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_mode_is_missing';
    protected const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_mode_is_incorrect';

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToPriceFacadeInterface $priceFacade
     */
    public function __construct(QuoteToStoreFacadeInterface $storeFacade, QuoteToPriceFacadeInterface $priceFacade)
    {
        $this->storeFacade = $storeFacade;
        $this->priceFacade = $priceFacade;
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
        $quoteValidationResponseTransfer = $this->validatePriceMode($quoteTransfer, $quoteValidationResponseTransfer);

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
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_CURRENCY_DATA_IS_INCORRECT, $currencyCode);
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function validatePriceMode(
        QuoteTransfer $quoteTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        $priceMode = $quoteTransfer->getPriceMode();

        if (!$priceMode) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_PRICE_MODE_DATA_IS_MISSING);
        }

        $availablePiceModes = $this->priceFacade->getPriceModes();

        if (!isset($availablePiceModes[$priceMode])) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT, $priceMode);
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
