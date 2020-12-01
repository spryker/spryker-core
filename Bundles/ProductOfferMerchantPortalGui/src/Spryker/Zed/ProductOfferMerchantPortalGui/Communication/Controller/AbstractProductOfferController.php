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
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::COL_STORE
     */
    protected const KEY_COLUMN_STORE = 'store';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::COL_CURRENCY
     */
    protected const KEY_COLUMN_CURRENCY = 'currency';

    protected const VALUE_PROPERTY_PATH_MONEY_VALUE = 'moneyValue';

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
        $validationErrorTransfers = $priceProductOfferCollectionValidationResponseTransfer->getValidationErrors();

        foreach ($validationErrorTransfers as $validationErrorTransfer) {
            if (!$validationErrorTransfer->getPropertyPath()) {
                continue;
            }

            $initialData = $this->addInitialDataErrors($validationErrorTransfer, $initialData);
        }

        return $initialData;
    }

    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ValidationErrorTransfer $validationErrorTransfer
     * @param array $initialData
     *
     * @return array
     */
    protected function addInitialDataErrors(ValidationErrorTransfer $validationErrorTransfer, array $initialData): array
    {
        $propertyPath = $this->extractPropertyPatchValues($validationErrorTransfer->getPropertyPath());

        if (!$propertyPath || !is_array($propertyPath)) {
            return $initialData;
        }

        $rowNumber = (int)$propertyPath[0] === 0 ? 0 : ((int)$propertyPath[0] - 1) % 2;
        $isRowError = count($propertyPath) < 3;
        $errorMessage = $validationErrorTransfer->getMessage();

        if ($isRowError) {
            $initialData[static::KEY_ERRORS][$rowNumber][static::KEY_ROW_ERROR] = $errorMessage;
            $initialData[static::KEY_ERRORS][$rowNumber][static::KEY_COLUMN_ERRORS][static::KEY_COLUMN_STORE] = true;
            $initialData[static::KEY_ERRORS][$rowNumber][static::KEY_COLUMN_ERRORS][static::KEY_COLUMN_CURRENCY] = true;

            return $initialData;
        }

        $columnId = $this->transformPropertyPathToColumnId($propertyPath);

        if (!$columnId) {
            return $initialData;
        }

        $initialData[static::KEY_ERRORS][$rowNumber][static::KEY_COLUMN_ERRORS][$columnId] = $errorMessage;

        return $initialData;
    }

    /**
     * @param string $propertyPath
     *
     * @return string[]
     */
    protected function extractPropertyPatchValues(string $propertyPath): array
    {
        $propertyPath = str_replace('[', '', $propertyPath);
        $propertyPathValues = explode(']', $propertyPath);

        if (!is_array($propertyPathValues)) {
            return [];
        }

        return $propertyPathValues;
    }

    /**
     * @param string[] $propertyPath
     *
     * @return string
     */
    protected function transformPropertyPathToColumnId(array $propertyPath): string
    {
        if (!isset($propertyPath[1])) {
            return '';
        }

        if ($propertyPath[1] === static::VALUE_PROPERTY_PATH_MONEY_VALUE) {
            $priceTypes = $this->getFactory()->getPriceProductFacade()->getPriceTypeValues();
            $priceTypeName = mb_strtolower($priceTypes[$propertyPath[0]]->getName());

            if (!isset($propertyPath[2])) {
                return '';
            }

            return sprintf('%s[%s][%s]', $priceTypeName, (string)$propertyPath[1], (string)$propertyPath[2]);
        }

        return (string)$propertyPath[1];
    }

    /**
     * @phpstan-param array<string, mixed> $responseData
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $productOfferForm
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $responseData
     * @param \Symfony\Component\Form\FormInterface $productOfferForm
     * @param bool|null $isPriceProductOffersValid
     *
     * @return array
     */
    protected function addValidationNotifications(
        array $responseData,
        FormInterface $productOfferForm,
        ?bool $isPriceProductOffersValid = true
    ): array {
        if (!$productOfferForm->isValid() || !$isPriceProductOffersValid) {
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
