<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class AbstractProductOfferController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string[]
     */
    protected function getProductAttributes(
        LocaleTransfer $localeTransfer,
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): array {
        $productAbstractLocalizedAttributes = $this->getLocalizedAttributesByLocale(
            $productAbstractTransfer->getLocalizedAttributes()->getArrayCopy(),
            $localeTransfer
        );
        $productConcreteLocalizedAttributes = $this->getLocalizedAttributesByLocale(
            $productConcreteTransfer->getLocalizedAttributes()->getArrayCopy(),
            $localeTransfer
        );
        $rawProductAttributesTransfer = (new RawProductAttributesTransfer())
            ->setAbstractAttributes($productAbstractTransfer->getAttributes())
            ->setAbstractLocalizedAttributes($productAbstractLocalizedAttributes)
            ->setConcreteAttributes($productConcreteTransfer->getAttributes())
            ->setConcreteLocalizedAttributes($productConcreteLocalizedAttributes);

        return $this->getFactory()->getProductFacade()->combineRawProductAttributes($rawProductAttributesTransfer);
    }

    /**
     * @phpstan-param array<\Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     *
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    protected function getLocalizedAttributesByLocale(array $localizedAttributes, LocaleTransfer $localeTransfer): array
    {
        foreach ($localizedAttributes as $localizedAttributesTransfer) {
            if ($localizedAttributesTransfer->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributesTransfer->getAttributes();
            }
        }

        return [];
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $formName
     *
     * @return array
     */
    protected function setDefaultInitialData(Request $request, string $formName): array
    {
        $requestTableData = $request->get($formName);
        $requestTableData = $this->getFactory()->getUtilEncodingService()->decodeJson(
            $requestTableData[PriceProductOfferTableViewTransfer::PRICES],
            true
        );

        return $initialData = [
            GuiTableEditableInitialDataTransfer::DATA => $requestTableData,
            GuiTableEditableInitialDataTransfer::ERRORS => [],
        ];
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @phpstan-param array<mixed> $initialData
     *
     * @phpstan-return array<mixed>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param array $initialData
     *
     * @return array
     */
    protected function validateProductOfferPrices(ArrayObject $priceProductTransfers, array $initialData): array
    {
        $priceProductOfferCollectionValidationResponseTransfer = $this->getFactory()
            ->getPriceProductOfferFacade()
            ->validateProductOfferPrices($priceProductTransfers);

        if (!$priceProductOfferCollectionValidationResponseTransfer->getIsSuccessful()) {
            return $this->getFactory()
                ->createPriceProductOfferMapper()
                ->mapPriceProductOfferCollectionValidationResponseTransferToInitialDataErrors(
                    $priceProductOfferCollectionValidationResponseTransfer,
                    $initialData
                );
        }

        return $initialData;
    }

    /**
     * @phpstan-param array<string, mixed> $responseData
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productOfferForm
     * @phpstan-param array<mixed> $initialData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $responseData
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param array $initialData
     *
     * @return array
     */
    protected function addValidationNotifications(
        array $responseData,
        FormInterface $productOfferForm,
        array $initialData
    ): array {
        if (!$productOfferForm->isValid() || !empty($initialData['errors'])) {
            $responseData['notifications'] = [
                [
                    'type' => 'error',
                    'message' => 'The Offer is not saved.',
                ],
                [
                    'type' => 'error',
                    'message' => 'To create an Offer please resolve all errors',
                ],
            ];
        }

        return $responseData;
    }
}
