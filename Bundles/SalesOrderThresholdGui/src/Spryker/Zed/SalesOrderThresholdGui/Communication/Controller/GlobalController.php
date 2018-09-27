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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            return $this->handleFormSubmission($request, $globalThresholdForm);
        }

        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        return $this->viewResponse([
            'localeCollection' => $localeCollection,
            'globalThresholdForm' => $globalThresholdForm->createView(),
        ]);
    }

    /***
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $globalThresholdForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleFormSubmission(Request $request, FormInterface $globalThresholdForm): RedirectResponse
    {
        $data = $globalThresholdForm->getData();
        $hardSalesOrderThresholdTransfer = $this->getFactory()
            ->createGlobalHardThresholdFormMapper()
            ->map($data, $this->createSalesOrderThresholdTransfer(
                $data[GlobalThresholdType::FIELD_ID_THRESHOLD_HARD]
            ));

        $this->saveSalesOrderThreshold($hardSalesOrderThresholdTransfer);

        $softSalesOrderThresholdTransfer = $this->createSalesOrderThresholdTransfer(
            $data[GlobalThresholdType::FIELD_ID_THRESHOLD_SOFT]
        );

        if ($data[GlobalThresholdType::FIELD_SOFT_STRATEGY] &&
            $this->getFactory()->createGlobalSoftThresholdFormMapperResolver()->hasGlobalThresholdMapperByStrategyKey(
                $data[GlobalThresholdType::FIELD_SOFT_STRATEGY]
            )) {
            $softSalesOrderThresholdTransfer = $this->getFactory()
                ->createGlobalSoftThresholdFormMapperResolver()
                ->resolveGlobalThresholdMapperByStrategyKey($data[GlobalThresholdType::FIELD_SOFT_STRATEGY])
                ->map($data, $softSalesOrderThresholdTransfer);
        }

        $this->saveSalesOrderThreshold($softSalesOrderThresholdTransfer);

        $this->addSuccessMessage(sprintf(
            'The Global Threshold is saved successfully.'
        ));

        return $this->redirectResponse($request->getRequestUri());
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return void
     */
    protected function saveSalesOrderThreshold(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): void
    {
        if (empty($salesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold())) {
            if ($salesOrderThresholdTransfer->getIdSalesOrderThreshold()) {
                $this->getFactory()
                    ->getSalesOrderThresholdFacade()
                    ->deleteSalesOrderThreshold($salesOrderThresholdTransfer);
            }

            return;
        }

        $this->getFactory()
            ->getSalesOrderThresholdFacade()
            ->saveSalesOrderThreshold($salesOrderThresholdTransfer);
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
     * @param int|null $idSalesOrderThreshold
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function createSalesOrderThresholdTransfer(?int $idSalesOrderThreshold): SalesOrderThresholdTransfer
    {
        return (new SalesOrderThresholdTransfer())
            ->setIdSalesOrderThreshold($idSalesOrderThreshold)
            ->setSalesOrderThresholdValue(new SalesOrderThresholdValueTransfer());
    }
}
