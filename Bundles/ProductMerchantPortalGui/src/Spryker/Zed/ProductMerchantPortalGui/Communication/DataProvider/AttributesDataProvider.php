<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface;

class AttributesDataProvider implements AttributesDataProviderInterface
{
    /**
     * @var string
     */
    protected const DATA_KEY_NAME = 'name';
    /**
     * @var string
     */
    protected const DATA_KEY_VALUE = 'value';
    /**
     * @var string
     */
    protected const DATA_KEY_ATTRIBUTES = 'attributes';
    /**
     * @var string
     */
    protected const DATA_KEY_ATTRIBUTE = 'attribute';
    /**
     * @var string
     */
    protected const DATA_KEY_SUPER_ATTRIBUTES = 'superAttributes';
    /**
     * @var string
     */
    protected const DATA_KEY_SKU = 'sku';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface
     */
    protected $localizedAttributesExtractor;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor\LocalizedAttributesExtractorInterface $localizedAttributesExtractor
     */
    public function __construct(LocalizedAttributesExtractorInterface $localizedAttributesExtractor)
    {
        $this->localizedAttributesExtractor = $localizedAttributesExtractor;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     *
     * @return array<mixed>
     */
    public function getProductAttributesData(array $productManagementAttributeTransfers): array
    {
        $productAttributesData = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $attributes = [];
            foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
                $attributes[] = [
                    static::DATA_KEY_VALUE => ucfirst($productManagementAttributeValueTransfer->getValueOrFail()),
                    static::DATA_KEY_NAME => ucfirst($productManagementAttributeValueTransfer->getValueOrFail()),
                ];
            }

            $productAttributesData[] = [
                static::DATA_KEY_NAME => $productManagementAttributeTransfer->getKeyOrFail(),
                static::DATA_KEY_VALUE => $productManagementAttributeTransfer->getKeyOrFail(),
                static::DATA_KEY_ATTRIBUTES => $attributes,
            ];
        }

        usort($productAttributesData, [$this, 'sortProductAttributesData']);

        return $productAttributesData;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<mixed>
     */
    public function getExistingConcreteProductData(
        MerchantProductTransfer $merchantProductTransfer,
        array $productManagementAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): array {
        $superAttributeKeys = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $superAttributeKeys[] = $productManagementAttributeTransfer->getKeyOrFail();
        }

        $superAttributesData = [];
        foreach ($merchantProductTransfer->getProducts() as $productConcreteTransfer) {
            $superAttributesData[] = $this->getSuperAttributesDataItem(
                $productConcreteTransfer,
                $superAttributeKeys,
                $localeTransfer
            );
        }

        return $superAttributesData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<string> $superAttributeKeys
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getSuperAttributesDataItem(
        ProductConcreteTransfer $productConcreteTransfer,
        array $superAttributeKeys,
        LocaleTransfer $localeTransfer
    ): array {
        $superAttributes = [];
        $attributes = $productConcreteTransfer->getAttributes();
        ksort($attributes);
        foreach ($attributes as $attributeKey => $attributeValue) {
            if (!in_array($attributeKey, $superAttributeKeys)) {
                continue;
            }

            $superAttributes[] = [
                static::DATA_KEY_VALUE => $attributeKey,
                static::DATA_KEY_NAME => $attributeKey,
                static::DATA_KEY_ATTRIBUTE => [
                    static::DATA_KEY_VALUE => $attributeValue,
                    static::DATA_KEY_NAME => $attributeValue,
                ],
            ];
        }

        $localizedAttributesTransfer = $this->localizedAttributesExtractor->extractLocalizedAttributes(
            $productConcreteTransfer->getLocalizedAttributes(),
            $localeTransfer
        );

        return [
            static::DATA_KEY_NAME => $localizedAttributesTransfer ? $localizedAttributesTransfer->getName() : null,
            static::DATA_KEY_SKU => $productConcreteTransfer->getSku(),
            static::DATA_KEY_SUPER_ATTRIBUTES => $superAttributes,
        ];
    }

    /**
     * @param array<mixed> $a
     * @param array<mixed> $b
     *
     * @return int
     */
    protected function sortProductAttributesData(array $a, array $b): int
    {
        return strcmp($a[static::DATA_KEY_VALUE], $b[static::DATA_KEY_VALUE]);
    }
}
