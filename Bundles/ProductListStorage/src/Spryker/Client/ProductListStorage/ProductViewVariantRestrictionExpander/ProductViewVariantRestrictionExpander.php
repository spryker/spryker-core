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
use Spryker\Shared\ProductListStorage\ProductListStorageConfig;

/**
 * @see \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilter
 */
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
     * @deprecated Will be removed without replacement. Not recommended to use with spryker/product-storage ^1.4.0.
     *
     * @see \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilter::filterAbstractProductVariantsData()
     *
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

        if (empty($restrictedProductConcreteIds)) {
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
     * @param array<int> $restrictedProductConcreteIds
     *
     * @return array
     */
    protected function filterAttributeVariants(array $attributeVariants, array $restrictedProductConcreteIds): array
    {
        $attributeVariantsIterator = $this->createRecursiveIterator($attributeVariants);

        $unrestrictedAttributeVariants = [];
        foreach ($attributeVariantsIterator as $attributeVariantKey => $attributeVariantValue) {
            if (!$attributeVariantsIterator->callHasChildren()) {
                continue;
            }

            if (!array_key_exists(ProductListStorageConfig::VARIANT_LEAF_NODE_ID, $attributeVariantValue)) {
                continue;
            }

            if ($this->isRestrictedAttributeVariant($attributeVariantValue, $restrictedProductConcreteIds)) {
                continue;
            }

            $attributeVariantPath = $this->buildAttributeVariantPath($attributeVariantsIterator, $attributeVariantKey, $attributeVariantValue);
            $unrestrictedAttributeVariants = array_merge_recursive($unrestrictedAttributeVariants, $attributeVariantPath);
        }

        return $unrestrictedAttributeVariants;
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

            [$attributeKey, $attributeValue] = explode(ProductListStorageConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $filteredAttributeVariantKey);
            $filteredSuperAttributes[$attributeKey][$attributeValue] = $attributeValue;
        }

        return array_replace(array_fill_keys(array_keys($superAttributes), []), $filteredSuperAttributes);
    }

    /**
     * @param array<int> $productConcreteIds
     * @param array<int> $restrictedProductConcreteIds
     *
     * @return array<int>
     */
    protected function filterProductConcreteIds(array $productConcreteIds, array $restrictedProductConcreteIds): array
    {
        return array_diff($productConcreteIds, $restrictedProductConcreteIds);
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $unrestrictedAttributeVariants
     *
     * @return array
     */
    protected function getAvailableAttributes(ProductViewTransfer $productViewTransfer, array $unrestrictedAttributeVariants): array
    {
        $availableAttributes = $this->getAvailableAttributesForSelectedOptions($unrestrictedAttributeVariants, $productViewTransfer->getSelectedAttributes());

        foreach ($productViewTransfer->getAvailableAttributes() as $attributeKey => $attributeValues) {
            $availableValues = $this->getAvailableAttributeValues($attributeKey, $attributeValues, $unrestrictedAttributeVariants);

            if (isset($availableAttributes[$attributeKey])) {
                $availableAttributes[$attributeKey] = array_intersect($availableAttributes[$attributeKey], $availableValues);

                continue;
            }

            $availableAttributes[$attributeKey] = $attributeValues;
        }

        return $availableAttributes;
    }

    /**
     * @param array $unrestrictedAttributeVariants
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function getAvailableAttributesForSelectedOptions(array $unrestrictedAttributeVariants, array $selectedAttributes = []): array
    {
        $availableAttributes = $availableAttributesForSelectedOptions = [];

        foreach ($selectedAttributes as $key => $selectedAttribute) {
            $selectedAttributeKey = $this->getAttributeKeyValue($key, $selectedAttributes[$key]);

            if (isset($unrestrictedAttributeVariants[$selectedAttributeKey])) {
                $availableAttributes = array_merge($availableAttributes, array_keys($unrestrictedAttributeVariants[$selectedAttributeKey]));
            }
        }

        foreach ($availableAttributes as $availableAttributeKey) {
            [$availableAttributeKey, $availableAttributeValue] = explode(ProductListStorageConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $availableAttributeKey);
            $availableAttributesForSelectedOptions[$availableAttributeKey][] = $availableAttributeValue;
        }

        return $availableAttributesForSelectedOptions;
    }

    /**
     * @param string $attributeKey
     * @param array $attributeValues
     * @param array $unrestrictedAttributeVariants
     *
     * @return array
     */
    protected function getAvailableAttributeValues(
        string $attributeKey,
        array $attributeValues,
        array $unrestrictedAttributeVariants
    ): array {
        $availableAttributeValues = [];

        foreach ($attributeValues as $attributeValue) {
            $attributeKeyValue = $this->getAttributeKeyValue($attributeKey, $attributeValue);

            if ($this->isAttributeKeyValueAvailable($attributeKeyValue, $unrestrictedAttributeVariants)) {
                $availableAttributeValues[] = $attributeValue;
            }
        }

        return $availableAttributeValues;
    }

    /**
     * @param string $attributeKeyValue
     * @param array $unrestrictedAttributeVariants
     *
     * @return bool
     */
    protected function isAttributeKeyValueAvailable(string $attributeKeyValue, array $unrestrictedAttributeVariants): bool
    {
        return array_key_exists($attributeKeyValue, $unrestrictedAttributeVariants);
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
        $attributeVariantPath = [
            $attributeVariantKey => $attributeVariantValue,
        ];
        for ($i = $iterator->getDepth() - 1; $i >= 0; $i--) {
            $attributeVariantPath = [
                $iterator->getSubIterator($i)->key() => $attributeVariantPath,
            ];
        }

        return $attributeVariantPath;
    }

    /**
     * @param array $attributeVariantValue
     * @param array<int> $restrictedProductIds
     *
     * @return bool
     */
    protected function isRestrictedAttributeVariant(array $attributeVariantValue, array $restrictedProductIds): bool
    {
        return in_array($attributeVariantValue[ProductListStorageConfig::VARIANT_LEAF_NODE_ID], $restrictedProductIds);
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
        return implode(ProductListStorageConfig::ATTRIBUTE_MAP_PATH_DELIMITER, [
            $attributeKey,
            $attributeValue,
        ]);
    }
}
