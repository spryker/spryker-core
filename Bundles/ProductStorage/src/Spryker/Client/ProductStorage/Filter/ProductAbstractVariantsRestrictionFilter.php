<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Filter;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface;
use Spryker\Shared\Product\ProductConfig;

class ProductAbstractVariantsRestrictionFilter implements ProductAbstractVariantsRestrictionFilterInterface
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
     * @param array $productAbstractStorageData
     *
     * @return array
     */
    public function filterAbstractProductVariantsData(array $productAbstractStorageData): array
    {
        $restrictedProductConcreteIds = $this->getRestrictedProductConcreteIds(
            $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_PRODUCT_CONCRETE_IDS]
        );

        if (empty($restrictedProductConcreteIds)) {
            return $productAbstractStorageData;
        }

        $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_PRODUCT_CONCRETE_IDS] = $this->filterProductConcreteIds(
            $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_PRODUCT_CONCRETE_IDS],
            $restrictedProductConcreteIds
        );

        $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_ATTRIBUTE_VARIANTS] = $this->filterAttributeVariants(
            $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_ATTRIBUTE_VARIANTS],
            $restrictedProductConcreteIds
        );

        $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_SUPER_ATTRIBUTES] = $this->filterSuperAttributes(
            $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_SUPER_ATTRIBUTES],
            $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::KEY_ATTRIBUTE_VARIANTS]
        );

        return $productAbstractStorageData;
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function getRestrictedProductConcreteIds(array $productConcreteIds): array
    {
        return array_reduce($productConcreteIds, function (array $restrictedProductConcreteIds, int $productConcreteId) {
            if ($this->productConcreteStorageReader->isProductConcreteRestricted($productConcreteId)) {
                $restrictedProductConcreteIds[] = $productConcreteId;
            }

            return $restrictedProductConcreteIds;
        }, []);
    }

    /**
     * @param array $productConcreteIds
     * @param int[] $restrictedProductConcreteIds
     *
     * @return array
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

            if (!array_key_exists(ProductConfig::VARIANT_LEAF_NODE_ID, $attributeVariantValue)) {
                continue;
            }

            if ($this->isRestrictedAttributeVariant($attributeVariantValue, $restrictedProductConcreteIds)) {
                continue;
            }

            $variantPath = $this->buildVariantPath($attributeVariantsIterator, $attributeVariantKey, $attributeVariantValue);
            $unRestrictedAttributeVariants = array_merge_recursive($unRestrictedAttributeVariants, $variantPath);
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

            [$attributeKey, $attributeValue] = explode(ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $filteredAttributeVariantKey);
            $filteredSuperAttributes[$attributeKey][$attributeValue] = $attributeValue;
        }

        return $filteredSuperAttributes;
    }

    /**
     * @param \RecursiveIteratorIterator $iterator
     * @param string $attributeVariantKey
     * @param array $attributeVariantValue
     *
     * @return array
     */
    protected function buildVariantPath(
        RecursiveIteratorIterator $iterator,
        string $attributeVariantKey,
        array $attributeVariantValue
    ): array {
        $variantPath[$attributeVariantKey] = $attributeVariantValue;
        for ($i = $iterator->getDepth() - 1; $i >= 0; $i--) {
            $variantPath = [
                $iterator->getSubIterator($i)->key() => $variantPath,
            ];
        }

        return $variantPath;
    }

    /**
     * @param array $attributeVariantValue
     * @param int[] $restrictedProductIds
     *
     * @return bool
     */
    protected function isRestrictedAttributeVariant(array $attributeVariantValue, array $restrictedProductIds): bool
    {
        return in_array($attributeVariantValue[ProductConfig::VARIANT_LEAF_NODE_ID], $restrictedProductIds);
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
        return implode(ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER, [
            $attributeKey,
            $attributeValue,
        ]);
    }
}
