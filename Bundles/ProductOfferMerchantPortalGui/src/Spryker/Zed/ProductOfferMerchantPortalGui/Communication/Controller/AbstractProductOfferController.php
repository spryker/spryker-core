<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class AbstractProductOfferController extends AbstractController
{
    protected const PARAM_PRODUCT_OFFER_FORM_NAME = 'productOfferCreate';
    protected const KEY_PRICES = 'prices';
    protected const KEY_DATA = 'data';
    protected const KEY_ERRORS = 'errors';
    protected const KEY_ROW_ERROR = 'rowError';
    protected const KEY_COLUMN_ERRORS = 'columnErrors';

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
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionValidationResponseTransfer $priceProductOfferCollectionValidationResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function prepareInitialDataForGuiTableConfiguration(
        PriceProductOfferCollectionValidationResponseTransfer $priceProductOfferCollectionValidationResponseTransfer,
        Request $request
    ): array {
        $productOfferCreateData = $request->get(static::PARAM_PRODUCT_OFFER_FORM_NAME);
        $initialData[static::KEY_ERRORS] = [];
        $requestTableData = $this->getFactory()->getUtilEncodingService()->decodeJson($productOfferCreateData[static::KEY_PRICES], true);

        $initialData[static::KEY_DATA] = $requestTableData;
        $priceProductOfferValidationErrorTransfers = $priceProductOfferCollectionValidationResponseTransfer->getErrors();

        foreach ($priceProductOfferValidationErrorTransfers as $priceProductOfferValidationErrorTransfer) {
            $validationErrorTransfers = $priceProductOfferValidationErrorTransfer->getValidationErrors();
            $priceProductTransfer = $priceProductOfferValidationErrorTransfer->getPriceProduct();
            $priceTypeName = mb_strtolower($priceProductTransfer->getPriceType()->getName());

            foreach ($validationErrorTransfers as $validationErrorTransfer) {
                $errorMessage = $validationErrorTransfer->getMessage();
                $propertyPath = $validationErrorTransfer->getPropertyPath();
                $columnId = $priceTypeName . $propertyPath;

                foreach ($requestTableData as $key => $data) {
                    if (!$propertyPath) {
                        $initialData[static::KEY_ERRORS][$key][static::KEY_ROW_ERROR] = $errorMessage;

                        break;
                    }

                    $initialData[static::KEY_ERRORS][$key][static::KEY_COLUMN_ERRORS][$columnId] = $errorMessage;
                }
            }
        }

        return $initialData;
    }
}
