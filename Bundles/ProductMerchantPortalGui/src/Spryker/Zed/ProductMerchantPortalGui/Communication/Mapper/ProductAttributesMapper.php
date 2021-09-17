<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableEditableDataErrorTransfer;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;
use Symfony\Component\Form\FormErrorIterator;

class ProductAttributesMapper implements ProductAttributesMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProvider
     */
    protected $productAttributeDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductAttributeDataProvider $productAttributeDataProvider
     */
    public function __construct(
        ProductAttributeDataProvider $productAttributeDataProvider
    ) {
        $this->productAttributeDataProvider = $productAttributeDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormErrorIterator $errors
     * @param array $attributesInitialData
     *
     * @return array<string[]>
     */
    public function mapErrorsToAttributesData(FormErrorIterator $errors, array $attributesInitialData): array
    {
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($errors as $error) {
            if (!method_exists($error, 'getMessageParameters')) {
                continue;
            }
            $messageParameters = $error->getMessageParameters();
            $attributesRowNumber = $messageParameters['attributesRowNumber'] ?? null;

            if ($attributesRowNumber !== null) {
                $attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS][$attributesRowNumber][GuiTableEditableDataErrorTransfer::ROW_ERROR] = $error->getMessage();
            }
        }

        if (isset($attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS])) {
            $attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS] = $this->fillNotExistingNumericArrayElements(
                $attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS]
            );
        }

        return $attributesInitialData;
    }

    /**
     * @param array<string[][]> $attributesInitialData
     * @param array<string> $attributes
     *
     * @return array<string>
     */
    public function mapAttributesDataToProductAttributes(array $attributesInitialData, array $attributes): array
    {
        foreach ($attributesInitialData[GuiTableEditableInitialDataTransfer::DATA] as $attribute) {
            $newAttributeName = $attribute[ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME];
            $defaultAttributeValue = $attribute[ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_DEFAULT];

            unset($attributes[$newAttributeName]);

            if ($defaultAttributeValue) {
                $attributes[$newAttributeName] = $defaultAttributeValue;
            }
        }

        return $attributes;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     *
     * @param array<string[][]> $attributesInitialData
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    public function mapAttributesDataToLocalizedAttributesTransfers(array $attributesInitialData, ArrayObject $localizedAttributesTransfers): ArrayObject
    {
        foreach ($attributesInitialData[GuiTableEditableInitialDataTransfer::DATA] as $newAttribute) {
            $newAttributeName = $newAttribute[ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME] ?? '';

            if (empty($newAttributeName)) {
                return $localizedAttributesTransfers;
            }

            foreach ($newAttribute as $localeName => $attributeValue) {
                $localizedAttributeTransfer = $this->productAttributeDataProvider->findLocalizedAttributeByLocaleName($localizedAttributesTransfers, $localeName);

                if ($localizedAttributeTransfer) {
                    $localizedAttributes = $localizedAttributeTransfer->getAttributes();

                    unset($localizedAttributes[$newAttributeName]);

                    if ($attributeValue) {
                        $localizedAttributes[$newAttributeName] = $attributeValue;
                    }

                    $localizedAttributeTransfer->setAttributes($localizedAttributes);
                }
            }
        }

        return $localizedAttributesTransfers;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $destinationLocalizedAttributesTransfers
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $sourceLocalizedAttributesTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $destinationLocalizedAttributesTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $sourceLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    public function mapLocalizedAttributesNames(
        ArrayObject $destinationLocalizedAttributesTransfers,
        ArrayObject $sourceLocalizedAttributesTransfers
    ): ArrayObject {
        foreach ($destinationLocalizedAttributesTransfers as $destinationLocalizedAttributeTransfer) {
            $sourceLocalizedAttributeTransfer = $this->productAttributeDataProvider->findLocalizedAttribute(
                $sourceLocalizedAttributesTransfers,
                $destinationLocalizedAttributeTransfer->getLocaleOrFail()->getIdLocaleOrFail()
            );

            if ($sourceLocalizedAttributeTransfer) {
                $destinationLocalizedAttributeTransfer->setName($sourceLocalizedAttributeTransfer->getName());
            }
        }

        return $destinationLocalizedAttributesTransfers;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $destinationLocalizedAttributesTransfers
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $sourceLocalizedAttributesTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $destinationLocalizedAttributesTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $sourceLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    public function mapLocalizedDescriptions(ArrayObject $destinationLocalizedAttributesTransfers, ArrayObject $sourceLocalizedAttributesTransfers): ArrayObject
    {
        foreach ($destinationLocalizedAttributesTransfers as $destinationLocalizedAttribute) {
            $sourceLocalizedAttributesTransfer = $this->productAttributeDataProvider->findLocalizedAttribute(
                $sourceLocalizedAttributesTransfers,
                $destinationLocalizedAttribute->getLocaleOrFail()->getIdLocaleOrFail()
            );

            if ($sourceLocalizedAttributesTransfer) {
                $destinationLocalizedAttribute->setDescription($sourceLocalizedAttributesTransfer->getDescription());
            }
        }

        return $destinationLocalizedAttributesTransfers;
    }

    /**
     * @param array $attributesTableInitialData
     * @param array $data
     *
     * @return array
     */
    protected function fillNotExistingNumericArrayElements(array $attributesTableInitialData, array $data = []): array
    {
        if (!$attributesTableInitialData) {
            return $attributesTableInitialData;
        }

        $keys = array_keys($attributesTableInitialData);

        $max = max($keys);

        for ($index = 0; $index < $max; $index++) {
            if (!isset($attributesTableInitialData[$index])) {
                $attributesTableInitialData[$index] = $data;
            }
        }

        ksort($attributesTableInitialData);

        return $attributesTableInitialData;
    }
}
