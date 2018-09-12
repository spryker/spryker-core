<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Handler;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreWithCurrencyTransfer;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class StoreFormHandler implements FormHandlerInterface
{
    public const STORE_CURRENCY_DELIMITER = ';';

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        ManualOrderEntryGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
    {
        $storeCurrencyString = $quoteTransfer->getManualOrder()->getStoreCurrency();
        if (!$this->isValidStoreCurrencyString($storeCurrencyString)) {
            return $quoteTransfer;
        }

        list($storeName, $currencyCode) = explode(static::STORE_CURRENCY_DELIMITER, $storeCurrencyString);
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            if ($this->setStoreToQuote($quoteTransfer, $storeWithCurrencyTransfer, $storeName, $currencyCode)) {
                break;
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param string $storeCurrencyString
     *
     * @return bool
     */
    protected function isValidStoreCurrencyString($storeCurrencyString): bool
    {
        return strlen($storeCurrencyString) && strpos($storeCurrencyString, static::STORE_CURRENCY_DELIMITER) !== false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer $storeWithCurrencyTransfer
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return bool
     */
    protected function setStoreToQuote(
        QuoteTransfer $quoteTransfer,
        StoreWithCurrencyTransfer $storeWithCurrencyTransfer,
        $storeName,
        $currencyCode
    ): bool {
        $storeTransfer = $storeWithCurrencyTransfer->getStore();
        if ($storeName === $storeTransfer->getName()) {
            $quoteTransfer->setStore($storeTransfer);

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                if ($this->setCurrencyToQuote($quoteTransfer, $currencyCode, $currencyTransfer)) {
                    break;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $currencyCode
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    protected function setCurrencyToQuote(QuoteTransfer $quoteTransfer, $currencyCode, CurrencyTransfer $currencyTransfer): bool
    {
        if ($currencyCode === $currencyTransfer->getCode()) {
            $quoteTransfer->setCurrency($currencyTransfer);

            return true;
        }

        return false;
    }
}
