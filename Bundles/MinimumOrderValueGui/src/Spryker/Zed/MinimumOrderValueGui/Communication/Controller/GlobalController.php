<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Controller;

use Generated\Shared\Transfer\StoreCurrencyTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConfig;
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
        $globalMinimumOrderValueTransfers = $this->getGlobalMinimumOrderValueTransfers($storeCurrencyTransfer);

        $globalThresholdForm = $this->getFactory()->createGlobalThresholdForm($globalMinimumOrderValueTransfers, $storeCurrencyTransfer);
        $globalThresholdForm->handleRequest($request);

        if ($globalThresholdForm->isSubmitted() && $globalThresholdForm->isValid()) {
            $data = $globalThresholdForm->getData();
            $hardGlobalMinimumOrderValueTransfer = $this->getFactory()
                ->createGlobalHardThresholdFormMapper()
                ->map($data, $this->getFactory()->createGlobalMinimumOrderValueTransfer());

             $this->getFactory()
                ->getMinimumOrderValueFacade()
                ->setGlobalThreshold($hardGlobalMinimumOrderValueTransfer);

            if ($this->getFactory()->createGlobalSoftThresholdFormMapperResolver()->hasGlobalThresholdMapperByStrategyKey(
                $data[GlobalThresholdType::FIELD_SOFT_STRATEGY]
            )) {
                $softGlobalMinimumOrderValueTransfer = $this->getFactory()
                    ->createGlobalSoftThresholdFormMapperResolver()->resolveGlobalThresholdMapperByStrategyKey($data[GlobalThresholdType::FIELD_SOFT_STRATEGY])
                    ->map($data, $this->getFactory()->createGlobalMinimumOrderValueTransfer());

                $this->getFactory()
                    ->getMinimumOrderValueFacade()
                    ->setGlobalThreshold($softGlobalMinimumOrderValueTransfer);
            }

            $this->addSuccessMessage(sprintf(
                'The Global Threshold is saved successfully.'
            ));
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
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer[]
     */
    protected function getGlobalMinimumOrderValueTransfers(StoreCurrencyTransfer $storeCurrencyTransfer): array
    {
        return $this->getFactory()
            ->getMinimumOrderValueFacade()
            ->getGlobalThresholdsByStoreAndCurrency(
                $storeCurrencyTransfer->getStore(),
                $storeCurrencyTransfer->getCurrency()
            );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyTransfer
     */
    protected function getStoreCurrencyByRequest(Request $request): StoreCurrencyTransfer
    {
        $storeCurrency = $request->query->get(MinimumOrderValueGuiConfig::STORE_CURRENCY_URL_KEY);
        $storeTransfer = null;
        $currencyTransfer = null;

        if ($storeCurrency !== null) {
            return $this->getFactory()
                ->createStoreCurrencyFinder()
                ->getStoreCurrencyByString($storeCurrency);
        }

        return $this->getFactory()
            ->createStoreCurrencyFinder()
            ->getCurrentStoreCurrency();
    }
}
