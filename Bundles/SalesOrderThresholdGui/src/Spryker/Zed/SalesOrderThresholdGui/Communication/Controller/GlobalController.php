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
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractGlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 */
class GlobalController extends AbstractController
{
    protected const PARAM_STORE_CURRENCY_REQUEST = 'store_currency';
    protected const MESSAGE_UPDATE_SUCCESSFUL = 'The Global Thresholds is saved successfully.';

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
            return $this->handleFormSubmission($request, $globalThresholdForm, $storeTransfer, $currencyTransfer);
        }

        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        return $this->viewResponse([
            'localeCollection' => $localeCollection,
            'globalThresholdForm' => $globalThresholdForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $globalThresholdForm
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleFormSubmission(
        Request $request,
        FormInterface $globalThresholdForm,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): RedirectResponse {
        $data = $globalThresholdForm->getData();

        $this->handleThresholdData(
            $data[GlobalThresholdType::FIELD_HARD],
            SalesOrderThresholdGuiConfig::GROUP_HARD,
            $storeTransfer,
            $currencyTransfer
        );

        $this->handleThresholdData(
            $data[GlobalThresholdType::FIELD_SOFT],
            SalesOrderThresholdGuiConfig::GROUP_SOFT,
            $storeTransfer,
            $currencyTransfer
        );

        $this->addSuccessMessage(static::MESSAGE_UPDATE_SUCCESSFUL);

        return $this->redirectResponse($request->getRequestUri());
    }

    /**
     * @param array $thresholdData
     * @param string $strategyGroup
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return void
     */
    protected function handleThresholdData(
        array $thresholdData,
        string $strategyGroup,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): void {
        $salesOrderThresholdTransfer = $this->createSalesOrderThresholdTransfer(
            $thresholdData[AbstractGlobalThresholdType::FIELD_ID_THRESHOLD] ?? null,
            $storeTransfer,
            $currencyTransfer
        );

        if ($this->canMapThresholdData($thresholdData, $strategyGroup)) {
            $salesOrderThresholdTransfer = $this->getFactory()
                ->createGlobalThresholdFormMapperResolver()
                ->resolveGlobalThresholdFormMapperClassInstanceByStrategyGroup($strategyGroup)
                ->mapFormDataToTransfer($thresholdData, $salesOrderThresholdTransfer);
        }

        $this->saveSalesOrderThreshold($salesOrderThresholdTransfer);
    }

    /**
     * @param array $thresholdData
     * @param string $strategyGroup
     *
     * @return bool
     */
    protected function canMapThresholdData(array $thresholdData, string $strategyGroup): bool
    {
        if (!isset($thresholdData[AbstractGlobalThresholdType::FIELD_STRATEGY])) {
            return false;
        }

        return $this->getFactory()
            ->createGlobalThresholdFormMapperResolver()
            ->hasGlobalThresholdFormMapperByStrategyGroup(
                $strategyGroup
            );
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function createSalesOrderThresholdTransfer(
        ?int $idSalesOrderThreshold,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): SalesOrderThresholdTransfer {
        return (new SalesOrderThresholdTransfer())
            ->setIdSalesOrderThreshold($idSalesOrderThreshold)
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setSalesOrderThresholdValue(new SalesOrderThresholdValueTransfer());
    }
}
