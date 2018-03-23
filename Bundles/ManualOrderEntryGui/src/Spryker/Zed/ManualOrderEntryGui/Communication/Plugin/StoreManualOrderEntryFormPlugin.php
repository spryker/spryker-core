<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class StoreManualOrderEntryFormPlugin extends AbstractManualOrderEntryFormPlugin implements ManualOrderEntryFormPluginInterface
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null)
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
    public function handleData($quoteTransfer, &$form, $request)
    {
        $idStoreCurrency = $quoteTransfer->getIdStoreCurrency();
        if (!$this->isValidIdStoreCurrency($idStoreCurrency)) {
            return $quoteTransfer;
        }

        list($idStore, $idCurrency) = explode(';', $idStoreCurrency);
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStore();
            if ($idStore == $storeTransfer->getIdStore()) {
                $quoteTransfer->setStore($storeTransfer);

                foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                    if ($idCurrency == $currencyTransfer->getIdCurrency()) {
                        $quoteTransfer->setCurrency($currencyTransfer);
                        break;
                    }
                }
                break;
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleDataStepEngine($quoteTransfer, &$form, $request)
    {
        return $this->handleData($quoteTransfer, $form, $request);
    }

    /**
     * @param string $idStoreCurrency
     *
     * @return bool
     */
    protected function isValidIdStoreCurrency($idStoreCurrency)
    {
        return strlen($idStoreCurrency)
            && strpos($idStoreCurrency, ';') !== false;
    }
}
