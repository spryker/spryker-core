<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Grouper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

class ProductAttributeGrouper implements ProductAttributeGrouperInterface
{
    /**
     * @var string
     */
    protected const KEY_DATA = 'data';

    /**
     * @var string
     */
    protected const KEY_ERRORS = 'errors';

    /**
     * @var string
     */
    protected const COL_KEY_ATTRIBUTE_KEY = 'attribute_key';

    /**
     * @var string
     */
    public const COL_KEY_ATTRIBUTE_NAME = 'attribute_name';

    /**
     * @var string
     */
    public const COL_KEY_ATTRIBUTE_DEFAULT = 'attribute_default';

    /**
     * @var string
     */
    protected const EDITABLE_NEW_ROW = 'editableNewRow';

    /**
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array<string, string>
     */
    public function getLocalizedAttributeNamesGroupedByProductAttributeKey(
        array $productManagementAttributeTransfers,
        ?LocaleTransfer $localeTransfer
    ): array {
        $attributeNames = [];
        $localeName = null;
        if ($localeTransfer !== null) {
            $localeName = $localeTransfer->getLocaleName();
        }
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $attributeNames[$productManagementAttributeTransfer->getKey()] = $this->getLocalizedAttributeName(
                $productManagementAttributeTransfer,
                $localeName,
            );
        }

        return $attributeNames;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     * @param array<string, mixed> $productAttributes
     *
     * @return array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    public function getApplicableProductManagementAttributesGroupedByProductAttributeKey(
        array $productManagementAttributeTransfers,
        array $productAttributes
    ): array {
        $productManagementAttributesGroupedByAttributeKey = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $attributeKey = $productManagementAttributeTransfer->getKeyOrFail();
            if (isset($productAttributes[$attributeKey])) {
                continue;
            }
            $productManagementAttributesGroupedByAttributeKey[$attributeKey] = $productManagementAttributeTransfer;
        }

        return $productManagementAttributesGroupedByAttributeKey;
    }

    /**
     * @param array<int|string, mixed> $initialData
     * @param array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer> $groupProductManagementAttributeTransfers
     *
     * @return array<string, mixed>
     */
    public function getInitialDataGroupedByAttributeKey(
        array $initialData,
        array $groupProductManagementAttributeTransfers
    ): array {
        $initialDataGroupedByAttributeKey = [];
        foreach ($initialData[static::KEY_DATA] ?? [] as $i => $rowData) {
            $attributeKey = $rowData[static::COL_KEY_ATTRIBUTE_KEY];
            if (!isset($groupProductManagementAttributeTransfers[$attributeKey])) {
                continue;
            }

            $initialDataGroupedByAttributeKey[static::KEY_DATA][] = $rowData;
            $initialDataGroupedByAttributeKey[static::KEY_ERRORS][] = $initialData[static::KEY_ERRORS][$i] ?? [];
            unset($groupProductManagementAttributeTransfers[$attributeKey]);
        }

        return $this->expandInitialDataWithMissingRequiredAttributes(
            $initialDataGroupedByAttributeKey,
            $groupProductManagementAttributeTransfers,
        );
    }

    /**
     * @param array<string, mixed> $attributesInitialData
     * @param array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     *
     * @return array<string, mixed>
     */
    protected function expandInitialDataWithMissingRequiredAttributes(
        array $attributesInitialData,
        array $productManagementAttributeTransfers
    ): array {
        $attributesInitialData[static::KEY_DATA] = $attributesInitialData[static::KEY_DATA] ?? [];

        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $attributeKey = $productManagementAttributeTransfer->getKeyOrFail();
            if ($this->isAttributeKeyPresentInInitialData($attributesInitialData, $attributeKey)) {
                continue;
            }

            $rowData = [
                static::EDITABLE_NEW_ROW => true,
                static::COL_KEY_ATTRIBUTE_NAME => $attributeKey,
                static::COL_KEY_ATTRIBUTE_DEFAULT => '',
                static::COL_KEY_ATTRIBUTE_KEY => $attributeKey,
            ];

            foreach ($productManagementAttributeTransfer->getLocalizedKeys() as $localizedKeyTransfer) {
                $rowData[$localizedKeyTransfer->getLocaleName()] = '';
            }

            $attributesInitialData[static::KEY_DATA][] = $rowData;
        }

        return $attributesInitialData;
    }

    /**
     * @param array<string, mixed> $initialData
     * @param string $attributeKey
     *
     * @return bool
     */
    protected function isAttributeKeyPresentInInitialData(array $initialData, string $attributeKey): bool
    {
        foreach ($initialData[static::KEY_DATA] ?? [] as $rowData) {
            if ($rowData[static::COL_KEY_ATTRIBUTE_NAME] === $attributeKey) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param string|null $localeName
     *
     * @return string
     */
    protected function getLocalizedAttributeName(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        ?string $localeName
    ): string {
        foreach ($productManagementAttributeTransfer->getLocalizedKeys() as $localizedKeyTransfer) {
            if ($localizedKeyTransfer->getLocaleName() === $localeName) {
                /** @var string $localizedAttributeName */
                $localizedAttributeName = $localizedKeyTransfer->getKeyTranslation();

                return $localizedAttributeName;
            }
        }
        /** @var string $localizedAttributeName */
        $localizedAttributeName = $productManagementAttributeTransfer->getKey();

        return $localizedAttributeName;
    }
}
