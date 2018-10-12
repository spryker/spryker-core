<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductViewVariantRestrictionExpander;

use Generated\Shared\Transfer\ProductViewTransfer;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface;
use Spryker\Shared\Product\ProductConfig;

class ProductViewVariantRestrictionExpander implements ProductViewVariantRestrictionExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface
     */
    protected $productConcreteRestrictionReader;

    /**
     * @param \Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface $productConcreteRestrictionReader
     */
    public function __construct(
        ProductConcreteRestrictionReaderInterface $productConcreteRestrictionReader
    ) {
        $this->productConcreteRestrictionReader = $productConcreteRestrictionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductVariantData(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $productViewTransfer
            ->requireAttributeMap()
            ->getAttributeMap()
                ->requireProductConcreteIds();

        $restrictedProductConcreteIds = $this->getRestrictedProductConcreteIds($productViewTransfer->getAttributeMap()->getProductConcreteIds());

        if (!$restrictedProductConcreteIds) {
            return $productViewTransfer;
        }

        $productConcreteIds = $this->filterProductConcreteIds($productViewTransfer->getAttributeMap()->getProductConcreteIds(), $restrictedProductConcreteIds);
        $attributeVariants = $this->filterAttributeVariants($productViewTransfer->getAttributeMap()->getAttributeVariants(), $restrictedProductConcreteIds);
        $superAttributes = $this->filterSuperAttributes($productViewTransfer->getAttributeMap()->getSuperAttributes(), $attributeVariants);
        $availableAttributes = $this->getAvailableAttributes($productViewTransfer, $attributeVariants);

        $productViewTransfer
            ->setAvailableAttributes($availableAttributes)
            ->getAttributeMap()
                ->setProductConcreteIds($productConcreteIds)
                ->setAttributeVariants($attributeVariants)
                ->setSuperAttributes($superAttributes);

        return $productViewTransfer;
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
            if (!is_array($attributeVariantValue)) {
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
            if (!is_array($filteredAttributeVariant)) {
                continue;
            }

            if (array_key_exists(ProductConfig::VARIANT_LEAF_NODE_ID, $filteredAttributeVariant)) {
                continue;
            }

            [$attributeKey, $attributeValue] = explode(ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $filteredAttributeVariantKey);
            $filteredSuperAttributes[$attributeKey][] = $attributeValue;
        }

        return $filteredSuperAttributes;
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
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function getRestrictedProductConcreteIds(array $productConcreteIds): array
    {
        return array_reduce($productConcreteIds, function (array $restrictedProductConcreteIds, int $productConcreteId) {
            if ($this->productConcreteRestrictionReader->isProductConcreteRestricted($productConcreteId)) {
                $restrictedProductConcreteIds[] = $productConcreteId;
            }

            return $restrictedProductConcreteIds;
        }, []);
    }

    /**
     * @param array $productConcreteIds
     *
     * @return array
     */
    protected function getUnRestrictedProductConcreteIds(array $productConcreteIds): array
    {
        return array_filter($productConcreteIds, function ($productConcreteId) {
            return !$this->productConcreteRestrictionReader->isProductConcreteRestricted($productConcreteId);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $unRestrictedAttributeVariants
     *
     * @return array
     */
    protected function getAvailableAttributes(ProductViewTransfer $productViewTransfer, array $unRestrictedAttributeVariants): array
    {
        $availableAttributes = $this->getAvailableAttributesForSelectedOptions($unRestrictedAttributeVariants, $productViewTransfer->getSelectedAttributes());

        foreach ($productViewTransfer->getAvailableAttributes() as $attributeKey => $attributeValues) {
            $availableValues = $this->getAvailableAttributeValues($attributeKey, $attributeValues, $unRestrictedAttributeVariants);

            if (isset($availableAttributes[$attributeKey])) {
                $availableAttributes[$attributeKey] = array_intersect($availableAttributes[$attributeKey], $availableValues);
                continue;
            }

            $availableAttributes[$attributeKey] = $availableValues;
        }

        return $availableAttributes;
    }

    /**
     * @param array $unRestrictedAttributeVariants
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function getAvailableAttributesForSelectedOptions(array $unRestrictedAttributeVariants, array $selectedAttributes = []): array
    {
        $availableAttributes = $availableAttributesForSelectedOptions = [];

        foreach ($selectedAttributes as $key => $selectedAttribute) {
            $selectedAttributeKey = $this->getAttributeKeyValue($key, $selectedAttributes[$key]);

            if (isset($unRestrictedAttributeVariants[$selectedAttributeKey])) {
                $availableAttributes = array_merge($availableAttributes, array_keys($unRestrictedAttributeVariants[$selectedAttributeKey]));
            }
        }

        foreach ($availableAttributes as $availableAttributeKey) {
            [$availableAttributeKey, $availableAttributeValue] = explode(ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $availableAttributeKey);
            $availableAttributesForSelectedOptions[$availableAttributeKey][] = $availableAttributeValue;
        }

        return $availableAttributesForSelectedOptions;
    }

    /**
     * @param string $attributeKey
     * @param array $attributeValues
     * @param array $unRestrictedAttributeVariants
     *
     * @return array
     */
    protected function getAvailableAttributeValues(
        string $attributeKey,
        array $attributeValues,
        array $unRestrictedAttributeVariants
    ): array {
        $availableAttributeValues = [];

        foreach ($attributeValues as $attributeValue) {
            $attributeKeyValue = $this->getAttributeKeyValue($attributeKey, $attributeValue);

            if ($this->isAttributeKeyValueAvailable($attributeKeyValue, $unRestrictedAttributeVariants)) {
                $availableAttributeValues[] = $attributeValue;
            }
        }

        return $availableAttributeValues;
    }

    /**
     * @param string $attributeKeyValue
     * @param array $unRestrictedAttributeVariants
     *
     * @return bool
     */
    protected function isAttributeKeyValueAvailable(string $attributeKeyValue, array $unRestrictedAttributeVariants): bool
    {
        return array_key_exists($attributeKeyValue, $unRestrictedAttributeVariants);
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
        return implode(ProductConfig::VARIANT_LEAF_NODE_ID, [
            $attributeKey,
            $attributeValue,
        ]);
    }
}
