<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Controller;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\MerchantRelationshipMinimumOrderValueGuiCommunicationFactory getFactory()
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
            $this->handleFormSubmission($thresholdForm, $idMerchantRelationship);
        }
        $localeProvider = $this->getFactory()->createLocaleProvider();

        return $this->viewResponse([
            'localeCollection' => $localeProvider->getLocaleCollection(),
            'form' => $thresholdForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $thresholdForm
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    protected function handleFormSubmission(FormInterface $thresholdForm, int $idMerchantRelationship): void
    {
        $data = $thresholdForm->getData();
        $hardMinimumOrderValueTransfer = $this->getFactory()
            ->createHardThresholdFormMapper()
            ->map($data, $this->createMerchantRelationshipMinimumOrderValueTransfer());
        $hardMinimumOrderValueTransfer->getMerchantRelationship()
            ->setIdMerchantRelationship($idMerchantRelationship);

        $this->getFactory()
            ->getMerchantRelationshipMinimumOrderValueFacade()
            ->saveMerchantRelationshipMinimumOrderValue($hardMinimumOrderValueTransfer);

        if ($this->getFactory()->createSoftThresholdFormMapperResolver()->hasThresholdMapperByStrategyKey(
            $data[ThresholdType::FIELD_SOFT_STRATEGY]
        )) {
            $softMinimumOrderValueTransfer = $this->getFactory()
                ->createSoftThresholdFormMapperResolver()
                ->resolveThresholdMapperByStrategyKey($data[ThresholdType::FIELD_SOFT_STRATEGY])
                ->map($data, $this->createMerchantRelationshipMinimumOrderValueTransfer());

            $softMinimumOrderValueTransfer->getMerchantRelationship()
                ->setIdMerchantRelationship($idMerchantRelationship);

            $this->getFactory()
                ->getMerchantRelationshipMinimumOrderValueFacade()
                ->saveMerchantRelationshipMinimumOrderValue($softMinimumOrderValueTransfer);
        }

        $this->addSuccessMessage(static::MESSAGE_UPDATE_SUCCESSFUL);
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
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function createMerchantRelationshipMinimumOrderValueTransfer(): MerchantRelationshipMinimumOrderValueTransfer
    {
        return (new MerchantRelationshipMinimumOrderValueTransfer())
            ->setMinimumOrderValueThreshold(new MinimumOrderValueThresholdTransfer())
            ->setMerchantRelationship(new MerchantRelationshipTransfer());
    }
}
