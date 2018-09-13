<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Controller;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 */
class GlobalController extends AbstractController
{
    protected const PARAM_STORE_CURRENCY_REQUEST = 'store_currency';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $storeCurrencyRequestParam = $request->query->get(static::PARAM_STORE_CURRENCY_REQUEST);

        $currencyTransfer = $this->getCurrencyTransferFromRequest($storeCurrencyRequestParam);
        $storeTransfer = $this->getStoreTransferFromRequest($storeCurrencyRequestParam);

        $globalThresholdForm = $this->getFactory()->createGlobalThresholdForm($storeTransfer, $currencyTransfer);
        $globalThresholdForm->handleRequest($request);

        if ($globalThresholdForm->isSubmitted() && $globalThresholdForm->isValid()) {
            $data = $globalThresholdForm->getData();
            $hardSalesOrderThresholdTransfer = $this->getFactory()
                ->createGlobalHardThresholdFormMapper()
                ->map($data, $this->createSalesOrderThresholdTransfer());

             $this->getFactory()
                ->getSalesOrderThresholdFacade()
                ->saveSalesOrderThreshold($hardSalesOrderThresholdTransfer);

            if ($this->getFactory()->createGlobalSoftThresholdFormMapperResolver()->hasGlobalThresholdMapperByStrategyKey(
                $data[GlobalThresholdType::FIELD_SOFT_STRATEGY]
            )) {
                $softSalesOrderThresholdTransfer = $this->getFactory()
                    ->createGlobalSoftThresholdFormMapperResolver()->resolveGlobalThresholdMapperByStrategyKey($data[GlobalThresholdType::FIELD_SOFT_STRATEGY])
                    ->map($data, $this->createSalesOrderThresholdTransfer());

                $this->getFactory()
                    ->getSalesOrderThresholdFacade()
                    ->saveSalesOrderThreshold($softSalesOrderThresholdTransfer);
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
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransferFromRequest(?string $storeCurrencyRequestParam): CurrencyTransfer
    {
        return $this->getFactory()
            ->createStoreCurrencyFinder()
            ->getCurrencyTransferFromRequestParam($storeCurrencyRequestParam);
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
            ->getStoreTransferFromRequestParam($storeCurrencyRequestParam);
    }

    /**
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function createSalesOrderThresholdTransfer(): SalesOrderThresholdTransfer
    {
        return (new SalesOrderThresholdTransfer())
            ->setSalesOrderThresholdValue(new SalesOrderThresholdValueTransfer());
    }
}
