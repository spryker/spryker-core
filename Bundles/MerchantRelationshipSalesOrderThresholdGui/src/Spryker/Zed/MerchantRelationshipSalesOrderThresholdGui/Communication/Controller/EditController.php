<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Controller;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\ThresholdType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const PARAM_STORE_CURRENCY_REQUEST = 'store_currency';
    protected const REQUEST_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';
    protected const MESSAGE_UPDATE_SUCCESSFUL = 'The Merchant Relationship Threshold is saved successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $storeCurrencyRequestParam = $request->query->get(static::PARAM_STORE_CURRENCY_REQUEST);
        $idMerchantRelationship = $request->query->getInt(static::REQUEST_ID_MERCHANT_RELATIONSHIP);

        $currencyTransfer = $this->getCurrencyTransferFromRequestParam($storeCurrencyRequestParam);
        $storeTransfer = $this->getStoreTransferFromRequestParam($storeCurrencyRequestParam);

        $thresholdForm = $this->getFactory()->createThresholdForm(
            $idMerchantRelationship,
            $storeTransfer,
            $currencyTransfer
        );
        $thresholdForm->handleRequest($request);

        if ($thresholdForm->isSubmitted() && $thresholdForm->isValid()) {
            return $this->handleFormSubmission($request, $thresholdForm, $idMerchantRelationship);
        }

        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        return $this->viewResponse([
            'localeCollection' => $localeCollection,
            'form' => $thresholdForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $thresholdForm
     * @param int $idMerchantRelationship
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleFormSubmission(Request $request, FormInterface $thresholdForm, int $idMerchantRelationship): RedirectResponse
    {
        $data = $thresholdForm->getData();
        $hardSalesOrderThresholdTransfer = $this->getFactory()
            ->createHardThresholdFormMapper()
            ->map($data, $this->createMerchantRelationshipSalesOrderThresholdTransfer(
                $data[ThresholdType::FIELD_ID_MERCHANT_RELATIONSHIP_THRESHOLD_HARD],
                $idMerchantRelationship
            ));

        $this->saveMerchantRelationshipSalesOrderThreshold($hardSalesOrderThresholdTransfer);

        $softSalesOrderThresholdTransfer = $this->createMerchantRelationshipSalesOrderThresholdTransfer(
            $data[ThresholdType::FIELD_ID_MERCHANT_RELATIONSHIP_THRESHOLD_SOFT],
            $idMerchantRelationship
        );

        if ($data[ThresholdType::FIELD_SOFT_STRATEGY] &&
            $this->getFactory()->createSoftThresholdFormMapperResolver()->hasThresholdMapperByStrategyKey(
                $data[ThresholdType::FIELD_SOFT_STRATEGY]
            )) {
            $softSalesOrderThresholdTransfer = $this->getFactory()
                ->createSoftThresholdFormMapperResolver()
                ->resolveThresholdMapperByStrategyKey($data[ThresholdType::FIELD_SOFT_STRATEGY])
                ->map($data, $softSalesOrderThresholdTransfer);
        }

        $this->saveMerchantRelationshipSalesOrderThreshold($softSalesOrderThresholdTransfer);

        $this->addSuccessMessage(static::MESSAGE_UPDATE_SUCCESSFUL);

        return $this->redirectResponse($request->getRequestUri());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return void
     */
    protected function saveMerchantRelationshipSalesOrderThreshold(
        MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
    ): void {
        if (empty($merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getThreshold())) {
            if ($merchantRelationshipSalesOrderThresholdTransfer->getIdMerchantRelationshipSalesOrderThreshold()) {
                $this->getFactory()
                    ->getMerchantRelationshipSalesOrderThresholdFacade()
                    ->deleteMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);
            }

            return;
        }

        $this->getFactory()
            ->getMerchantRelationshipSalesOrderThresholdFacade()
            ->saveMerchantRelationshipSalesOrderThreshold($merchantRelationshipSalesOrderThresholdTransfer);
    }

    /**
     * @param string|null $storeCurrencyRequestParam
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransferFromRequestParam(?string $storeCurrencyRequestParam): CurrencyTransfer
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
    protected function getStoreTransferFromRequestParam(?string $storeCurrencyRequestParam): StoreTransfer
    {
        return $this->getFactory()
            ->createStoreCurrencyFinder()
            ->getStoreTransferFromRequestParam($storeCurrencyRequestParam);
    }

    /**
     * @param int|null $idMerchantRelationshipSalesOrderThreshold
     * @param int|null $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    protected function createMerchantRelationshipSalesOrderThresholdTransfer(
        ?int $idMerchantRelationshipSalesOrderThreshold,
        ?int $idMerchantRelationship
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        return (new MerchantRelationshipSalesOrderThresholdTransfer())
            ->setIdMerchantRelationshipSalesOrderThreshold($idMerchantRelationshipSalesOrderThreshold)
            ->setSalesOrderThresholdValue(new SalesOrderThresholdValueTransfer())
            ->setMerchantRelationship(
                (new MerchantRelationshipTransfer())
                ->setIdMerchantRelationship($idMerchantRelationship)
            );
    }
}
