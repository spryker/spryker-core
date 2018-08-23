<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Controller;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\ThresholdType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\MerchantRelationshipMinimumOrderValueGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const STORE_CURRENCY_REQUEST_PARAM = 'store_currency';
    protected const REQUEST_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $storeCurrencyRequestParam = $request->query->get(static::STORE_CURRENCY_REQUEST_PARAM);
        $merchantRelationIdRequestParam = $request->query->getInt(static::REQUEST_ID_MERCHANT_RELATIONSHIP);

        $currencyTransfer = $this->getCurrencyTransferFromRequest($storeCurrencyRequestParam);
        $storeTransfer = $this->getStoreTransferFromRequest($storeCurrencyRequestParam);

        $minimumOrderValueTransfers = $this->getMinimumOrderValueTransfers($storeTransfer, $currencyTransfer);

        $thresholdForm = $this->getFactory()->createThresholdForm($minimumOrderValueTransfers, $storeTransfer, $currencyTransfer);
        $thresholdForm->handleRequest($request);

        if ($thresholdForm->isSubmitted() && $thresholdForm->isValid()) {
            $data = $thresholdForm->getData();
            $hardMinimumOrderValueTransfer = $this->getFactory()
                ->createHardThresholdFormMapper()
                ->map($data, $this->createMinimumOrderValueTransfer());

            $this->getFactory()
                ->getMinimumOrderValueFacade()
                ->saveMinimumOrderValue($hardMinimumOrderValueTransfer);

            if ($this->getFactory()->createSoftThresholdFormMapperResolver()->hasThresholdMapperByStrategyKey(
                $data[ThresholdType::FIELD_SOFT_STRATEGY]
            )) {
                $softMinimumOrderValueTransfer = $this->getFactory()
                    ->createSoftThresholdFormMapperResolver()->resolveThresholdMapperByStrategyKey($data[ThresholdType::FIELD_SOFT_STRATEGY])
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
            'form' => $thresholdForm->createView(),
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
            ->setThreshold(new MinimumOrderValueThresholdTransfer());
    }
}
