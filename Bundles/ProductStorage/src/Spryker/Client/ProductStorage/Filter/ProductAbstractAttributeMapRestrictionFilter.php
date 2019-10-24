<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Filter;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Client\ProductStorage\ProductStorageConfig;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface;

class ProductAbstractAttributeMapRestrictionFilter implements ProductAbstractAttributeMapRestrictionFilterInterface
{
    protected const KEY_PRODUCT_CONCRETE_IDS = 'product_concrete_ids';
    protected const KEY_ATTRIBUTE_VARIANTS = 'attribute_variants';
    protected const KEY_SUPER_ATTRIBUTES = 'super_attributes';

    /**
     * @var \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface
     */
    protected $productConcreteStorageReader;

    /**
     * @param \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface $productConcreteStorageReader
     */
    public function __construct(ProductConcreteStorageReaderInterface $productConcreteStorageReader)
    {
        $this->productConcreteStorageReader = $productConcreteStorageReader;
    }

    /**
     * @param array $productStorageData
     *
     * @return array
     */
    public function filterAbstractProductVariantsData(array $productStorageData): array
    {
        if (!isset($productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_PRODUCT_CONCRETE_IDS])) {
            return $productStorageData;
        }

        $restrictedProductConcreteIds = $this->getRestrictedProductConcreteIds(
            $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_PRODUCT_CONCRETE_IDS]
        );

        if (empty($restrictedProductConcreteIds)) {
            return $productStorageData;
        }

        $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_PRODUCT_CONCRETE_IDS] = $this->filterProductConcreteIds(
            $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_PRODUCT_CONCRETE_IDS],
            $restrictedProductConcreteIds
        );

        $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_ATTRIBUTE_VARIANTS] = $this->filterAttributeVariants(
            $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_ATTRIBUTE_VARIANTS],
            $restrictedProductConcreteIds
        );

        $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_SUPER_ATTRIBUTES] = $this->filterSuperAttributes(
            $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_SUPER_ATTRIBUTES],
            $productStorageData[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_ATTRIBUTE_VARIANTS]
        );

        return $productStorageData;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    protected function getRestrictedProductConcreteIds(array $productConcreteIds): array
    {
        $nonRestrictedConcreteIds = $this->productConcreteStorageReader->filterRestrictedProductConcreteIds($productConcreteIds);

        return array_diff($productConcreteIds, $nonRestrictedConcreteIds);
    }

    /**
     * @param int[] $productConcreteIds
     * @param int[] $restrictedProductConcreteIds
     *
     * @return int[]
     */
    protected function filterProductConcreteIds(array $productConcreteIds, array $restrictedProductConcreteIds): array
    {
        return array_diff($productConcreteIds, $restrictedProductConcreteIds);
    }

    /**
     * @param array $attributeVariants
     * @param int[] $restrictedProductConcreteIds
     *
     * @return array
     */
    protected function filterAttributeVariants(array $attributeVariants, array $restrictedProductConcreteIds): array
    {
        $attributeVariantsIterator = $this->createRecursiveIterator($attributeVariants);

        $unRestrictedAttributeVariants = [];
        foreach ($attributeVariantsIterator as $attributeVariantKey => $attributeVariantValue) {
            if (!$attributeVariantsIterator->callHasChildren()) {
                continue;
            }

            if (!array_key_exists(ProductStorageConfig::VARIANT_LEAF_NODE_ID, $attributeVariantValue)) {
                continue;
            }

            if ($this->isRestrictedAttributeVariant($attributeVariantValue, $restrictedProductConcreteIds)) {
                continue;
            }

            $attributeVariantPath = $this->buildAttributeVariantPath($attributeVariantsIterator, $attributeVariantKey, $attributeVariantValue);
            $unRestrictedAttributeVariants = array_merge_recursive($unRestrictedAttributeVariants, $attributeVariantPath);
        }

        return $unRestrictedAttributeVariants;
    }

    /**
     * @param array $superAttributes
     * @param array $filteredAttributeVariants
     *
     * @return array
     */
    protected function filterSuperAttributes(array $superAttributes, array $filteredAttributeVariants): array
    {
        $filteredSuperAttributes = [];
        $filteredAttributeVariantsIterator = $this->createRecursiveIterator($filteredAttributeVariants);
        foreach ($filteredAttributeVariantsIterator as $filteredAttributeVariantKey => $filteredAttributeVariant) {
            if (!$filteredAttributeVariantsIterator->callHasChildren()) {
                continue;
            }

            [$attributeKey, $attributeValue] = explode(ProductStorageConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $filteredAttributeVariantKey);
            $filteredSuperAttributes[$attributeKey][$attributeValue] = $attributeValue;
        }

        return array_replace(array_fill_keys(array_keys($superAttributes), []), $filteredSuperAttributes);
    }

    /**
     * @param \RecursiveIteratorIterator $iterator
     * @param string $attributeVariantKey
     * @param array $attributeVariantValue
     *
     * @return array
     */
    protected function buildAttributeVariantPath(
        RecursiveIteratorIterator $iterator,
        string $attributeVariantKey,
        array $attributeVariantValue
    ): array {
        $attributeVariantPath[$attributeVariantKey] = $attributeVariantValue;
        for ($i = $iterator->getDepth() - 1; $i >= 0; $i--) {
            $attributeVariantPath = [
                $iterator->getSubIterator($i)->key() => $attributeVariantPath,
            ];
        }

        return $attributeVariantPath;
    }

    /**
     * @param array $attributeVariantValue
     * @param int[] $restrictedProductIds
     *
     * @return bool
     */
    protected function isRestrictedAttributeVariant(array $attributeVariantValue, array $restrictedProductIds): bool
    {
        return in_array($attributeVariantValue[ProductStorageConfig::VARIANT_LEAF_NODE_ID], $restrictedProductIds);
    }

    /**
     * @param array $attributeVariants
     *
     * @return \RecursiveIteratorIterator
     */
    protected function createRecursiveIterator(array $attributeVariants): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveArrayIterator($attributeVariants),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }

    /**
     * @param string $attributeKey
     * @param string $attributeValue
     *
     * @return string
     */
    protected function getAttributeKeyValue(string $attributeKey, string $attributeValue): string
    {
        return implode(ProductStorageConfig::ATTRIBUTE_MAP_PATH_DELIMITER, [
            $attributeKey,
            $attributeValue,
        ]);
    }
}
