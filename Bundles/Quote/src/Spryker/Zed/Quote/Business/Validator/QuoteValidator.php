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
    protected const MESSAGE_STORE_DATA_IS_MISSING = 'Store data is missing.';
    protected const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'Price mode data is missing.';
    protected const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'Price mode "%s" does not exist.';
    protected const MESSAGE_CURRENCY_DATA_IS_MISSING = 'Currency data is missing.';
    protected const MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'Currency with ISO code "%s" does not exist for given store.';

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected $quoteValidationResponseTransfer;

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
    public function __construct(
        QuoteToStoreFacadeInterface $storeFacade,
        QuoteToPriceFacadeInterface $priceFacade
    ) {
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
        $this->initialize($quoteTransfer);
        $this->validateStoreAndCurrency();
        $this->validatePriceMode();

        return $this->quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this->quoteTransfer = clone $quoteTransfer;

        $this->quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsValid(true)
            ->setErrors(new ArrayObject());
    }

    /**
     * @param string $message
     * @param mixed ...$replacements
     *
     * @return void
     */
    protected function addValidationError(string $message, ...$replacements): void
    {
        if ($replacements) {
            $message = vsprintf($message, $replacements);
        }

        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($message);
        $this->quoteValidationResponseTransfer
            ->addErrors($quoteErrorTransfer)
            ->setIsValid(false);
    }

    /**
     * @return void
     */
    protected function validateStoreAndCurrency(): void
    {
        $storeTransfer = $this->quoteTransfer->getStore();

        if (!$storeTransfer || !$storeTransfer->getName()) {
            $this->addValidationError(static::MESSAGE_STORE_DATA_IS_MISSING);

            return;
        }

        try {
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        } catch (StoreNotFoundException $exception) {
            $this->addValidationError($exception->getMessage());

            return;
        }

        $this->validateCurrency($storeTransfer);
    }

    /**
     * @return void
     */
    protected function validatePriceMode(): void
    {
        $priceMode = $this->quoteTransfer->getPriceMode();

        if (!$priceMode) {
            $this->addValidationError(static::MESSAGE_PRICE_MODE_DATA_IS_MISSING);

            return;
        }

        $availablePiceModes = $this->priceFacade->getPriceModes();

        if (!isset($availablePiceModes[$priceMode])) {
            $this->addValidationError(static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT, $priceMode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function validateCurrency(StoreTransfer $storeTransfer): void
    {
        $currencyTransfer = $this->quoteTransfer->getCurrency();
        $currencyCode = $currencyTransfer->getCode();

        if (!$currencyTransfer || !$currencyCode) {
            $this->addValidationError(static::MESSAGE_CURRENCY_DATA_IS_MISSING);

            return;
        }

        if (array_search($currencyCode, $storeTransfer->getAvailableCurrencyIsoCodes()) === false) {
            $this->addValidationError(static::MESSAGE_CURRENCY_DATA_IS_INCORRECT, $currencyCode);
        }
    }
}
