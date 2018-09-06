<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Controller;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory getFactory()
 */
class GlobalController extends AbstractController
{
    protected const STORE_CURRENCY_REQUEST_PARAM = 'store_currency';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $storeCurrencyRequestParam = $request->query->get(static::STORE_CURRENCY_REQUEST_PARAM);

        $currencyTransfer = $this->getCurrencyTransferFromRequest($storeCurrencyRequestParam);
        $storeTransfer = $this->getStoreTransferFromRequest($storeCurrencyRequestParam);
        $minimumOrderValueTransfers = $this->getMinimumOrderValueTransfers($storeTransfer, $currencyTransfer);

        $globalThresholdForm = $this->getFactory()->createGlobalThresholdForm($minimumOrderValueTransfers, $storeTransfer, $currencyTransfer);
        $globalThresholdForm->handleRequest($request);

        if ($globalThresholdForm->isSubmitted() && $globalThresholdForm->isValid()) {
            $data = $globalThresholdForm->getData();
            $hardMinimumOrderValueTransfer = $this->getFactory()
                ->createGlobalHardThresholdFormMapper()
                ->map($data, $this->createMinimumOrderValueTransfer());

             $this->getFactory()
                ->getMinimumOrderValueFacade()
                ->saveMinimumOrderValue($hardMinimumOrderValueTransfer);

            if ($this->getFactory()->createGlobalSoftThresholdFormMapperResolver()->hasGlobalThresholdMapperByStrategyKey(
                $data[GlobalThresholdType::FIELD_SOFT_STRATEGY]
            )) {
                $softMinimumOrderValueTransfer = $this->getFactory()
                    ->createGlobalSoftThresholdFormMapperResolver()->resolveGlobalThresholdMapperByStrategyKey($data[GlobalThresholdType::FIELD_SOFT_STRATEGY])
                    ->map($data, $this->createMinimumOrderValueTransfer());

                $this->getFactory()
                    ->getMinimumOrderValueFacade()
                    ->saveMinimumOrderValue($softMinimumOrderValueTransfer);
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    protected function getMinimumOrderValueTransfers(StoreTransfer $storeTransfer, CurrencyTransfer $currencyTransfer): array
    {
        return $this->getFactory()
            ->getMinimumOrderValueFacade()
            ->findMinimumOrderValues(
                $storeTransfer,
                $currencyTransfer
            );
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransferFromRequest(?string $storeCurrencyRequestParam): CurrencyTransfer
    {
        return $this->getFactory()
            ->createStoreCurrencyFinder()
            ->getCurrencyTransferFromRequest($storeCurrencyRequestParam);
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransferFromRequest(?string $storeCurrencyRequestParam): StoreTransfer
    {
        return $this->getFactory()
            ->createStoreCurrencyFinder()
            ->getStoreTransferFromRequest($storeCurrencyRequestParam);
    }

    /**
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function createMinimumOrderValueTransfer(): MinimumOrderValueTransfer
    {
        return (new MinimumOrderValueTransfer())
            ->setMinimumOrderValueThreshold(new MinimumOrderValueThresholdTransfer());
    }
}
