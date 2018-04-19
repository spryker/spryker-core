<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Store\StoreType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class StoreManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    public function __construct()
    {
        $this->currencyFacade = $this->getFactory()->getCurrencyFacade();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return StoreType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null): FormInterface
    {
        return $this->getFactory()->createStoreForm($request, $dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData($quoteTransfer, &$form, Request $request): AbstractTransfer
    {
        $storeCurrencyString = $quoteTransfer->getIdStoreCurrency();
        if (!$this->isValidStoreCurrencyString($storeCurrencyString)) {
            return $quoteTransfer;
        }

        list($idStore, $idCurrency) = explode(';', $storeCurrencyString);
        $idStore = (int)$idStore;
        $idCurrency = (int)$idCurrency;
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            if ($this->setStoreToQuote($quoteTransfer, $storeWithCurrencyTransfer, $idStore, $idCurrency)) {
                break;
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return bool
     */
    public function isPreFilled($dataTransfer = null): bool
    {
        return false;
    }

    /**
     * @param string $storeCurrencyString
     *
     * @return bool
     */
    protected function isValidStoreCurrencyString($storeCurrencyString)
    {
        return strlen($storeCurrencyString)
            && strpos($storeCurrencyString, ';') !== false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\StoreWithCurrencyTransfer $storeWithCurrencyTransfer
     * @param int $idStore
     * @param int $idCurrency
     *
     * @return bool
     */
    protected function setStoreToQuote($quoteTransfer, $storeWithCurrencyTransfer, $idStore, $idCurrency)
    {
        $storeTransfer = $storeWithCurrencyTransfer->getStore();
        if ($idStore == $storeTransfer->getIdStore()) {
            $quoteTransfer->setStore($storeTransfer);

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                if ($this->setCurrencyToQuote($quoteTransfer, $idCurrency, $currencyTransfer)) {
                    break;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCurrency
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    protected function setCurrencyToQuote($quoteTransfer, $idCurrency, $currencyTransfer)
    {
        if ($idCurrency == $currencyTransfer->getIdCurrency()) {
            $quoteTransfer->setCurrency($currencyTransfer);

            return true;
        }

        return false;
    }
}
