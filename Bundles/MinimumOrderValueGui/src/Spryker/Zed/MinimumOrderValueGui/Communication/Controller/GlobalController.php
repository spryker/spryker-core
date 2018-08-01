<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreCurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory getFactory()
 */
class GlobalController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $storeCurrencyTransfer = $this->getStoreCurrencyByRequest($request);
        $minimumOrderValueTransfers = $this->getMinimumOrderValueTransfers($storeCurrencyTransfer);

        $globalThresholdForm = $this->getFactory()->createGlobalThresholdForm($minimumOrderValueTransfers, $storeCurrencyTransfer);
        $globalThresholdForm->handleRequest($request);

        if ($globalThresholdForm->isSubmitted() && $globalThresholdForm->isValid()) {
            try {
                $data = $globalThresholdForm->getData();
                $hardMinimumOrderValueTransfer = $this->getFactory()
                    ->createGlobalHardThresholdFormMapper()
                    ->map($data, new MinimumOrderValueTransfer());

                $softMinimumOrderValueTransfer = $this->getFactory()
                    ->createGlobalSoftThresholdFormMapperByStrategy($data[GlobalThresholdType::FIELD_SOFT_STRATEGY])
                    ->map($data, new MinimumOrderValueTransfer());

                $hardMinimumOrderValueTransfer = $this->getFactory()
                    ->getMinimumOrderValueFacade()
                    ->setStoreThreshold($hardMinimumOrderValueTransfer);

                $softMinimumOrderValueTransfer = $this->getFactory()
                    ->getMinimumOrderValueFacade()
                    ->setStoreThreshold($softMinimumOrderValueTransfer);

                $this->addSuccessMessage(sprintf(
                    'The Global Threshold is saved successfully.'
                ));
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }
        $localeProvider = $this->getFactory()->createLocaleProvider();

        return $this->viewResponse([
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'globalThresholdForm' => $globalThresholdForm->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer $storeCurrencyTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    protected function getMinimumOrderValueTransfers(StoreCurrencyTransfer $storeCurrencyTransfer): array
    {
        $minimumOrderValueFacade = $this->getFactory()->getMinimumOrderValueFacade();

        return $minimumOrderValueFacade->getGlobalThresholdsByStoreAndCurrency(
            $storeCurrencyTransfer->getStore(),
            $storeCurrencyTransfer->getCurrency()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrentCurrency(): CurrencyTransfer
    {
        $currentCurrency = $this->getFactory()
            ->getCurrencyFacade()
            ->getCurrent();
        $currentCurrency = $this->getFactory()
            ->getCurrencyFacade()
            ->fromIsoCode($currentCurrency->getCode());

        return $currentCurrency;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore(): StoreTransfer
    {
        $currentStore = $this->getFactory()
            ->getStoreFacade()
            ->getCurrentStore();

        return $currentStore;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    protected function getStoreCurrencyByRequest(Request $request): StoreCurrencyTransfer
    {
        $storeCurrency = $request->query->get(MinimumOrderValueGuiConstants::STORE_CURRENCY_URL_KEY);
        $storeTransfer = null;
        $currencyTransfer = null;

        if ($storeCurrency !== null) {
            $storeCurrencyTransfer = $this->getFactory()
                ->createStoreCurrencyFinder()
                ->findStoreCurrencyByString($storeCurrency);
            $storeTransfer = $storeCurrencyTransfer->getStore();
            $currencyTransfer = $storeCurrencyTransfer->getCurrency();
        }
        if (!$storeTransfer
            || !$currencyTransfer
        ) {
            $storeTransfer = $this->getCurrentStore();
            $currencyTransfer = $this->getCurrentCurrency();
        }
        return (new StoreCurrencyTransfer())
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer);
    }
}
