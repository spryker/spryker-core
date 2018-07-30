<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
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
        $minimumOrderValueTransfers = $this->getDefaultMinimumOrderValueTransfer();

        $globalThresholdForm = $this->getFactory()->createGlobalThresholdForm($minimumOrderValueTransfers);
        $globalThresholdForm->setData(['hard_value'=>333.44, 'global-threshold[hardValue]'=>77.88, 'soft_strategy'=>'option-soft-types-array']);
        $globalThresholdForm->handleRequest($request);

        if ($globalThresholdForm->isSubmitted() && $globalThresholdForm->isValid()) {
            try {
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
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    protected function getDefaultMinimumOrderValueTransfer(): array
    {
        $minimumOrderValueFacade = $this->getFactory()->getMinimumOrderValueFacade();

        $currentStore = $this->getCurrentStore();
        $currentCurrency = $this->getCurrentCurrency();

        return $minimumOrderValueFacade->getGlobalThresholdsByStoreAndCurrency($currentStore, $currentCurrency);
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
}
