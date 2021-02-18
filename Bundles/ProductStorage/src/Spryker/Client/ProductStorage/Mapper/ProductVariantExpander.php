<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Mapper;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface;
use Spryker\Shared\Product\ProductConfig;

class ProductVariantExpander implements ProductVariantExpanderInterface
{
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
     * @deprecated Use {@link \Spryker\Client\ProductStorage\Mapper\ProductVariantExpander::expandProductViewWithProductVariant()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductVariantData(ProductViewTransfer $productViewTransfer, $locale)
    {
        $productViewTransfer->requireAttributeMap();

        if ($this->isOnlyOneProductVariantCanBeSelected($productViewTransfer)) {
            return $this->getFirstProductVariant($productViewTransfer, $locale);
        }

        $selectedVariantNode = $this->getSelectedVariantNode($productViewTransfer);

        if ($productViewTransfer->getSelectedAttributes()) {
            $productViewTransfer = $this->getSelectedProductVariant($productViewTransfer, $locale, $selectedVariantNode);
        }

        if (!$productViewTransfer->getIdProductConcrete()) {
            $productViewTransfer = $this->setAvailableAttributes($selectedVariantNode, $productViewTransfer);
        }

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductVariant(
        ProductViewTransfer $productViewTransfer,
        string $localeName
    ): ProductViewTransfer {
        $productViewTransfer->requireAttributeMap();

        if ($this->isOnlyOneProductVariantCanBeSelected($productViewTransfer)) {
            return $this->getFirstProductVariant($productViewTransfer, $localeName);
        }

        $productViewTransfer = $this->setSingleValueAttributesAsSelected($productViewTransfer);
        $selectedVariantNode = $this->getSelectedVariantNode($productViewTransfer);

        if ($productViewTransfer->getSelectedAttributes()) {
            $productViewTransfer = $this->getSelectedProductVariant($productViewTransfer, $localeName, $selectedVariantNode);
        }

        if (!$productViewTransfer->getIdProductConcrete()) {
            $productViewTransfer = $this->setAvailableAttributes($selectedVariantNode, $productViewTransfer);
        }

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    protected function isOnlyOneProductVariantCanBeSelected(ProductViewTransfer $productViewTransfer): bool
    {
        return count($productViewTransfer->getAttributeMap()->getProductConcreteIds()) === 1 ||
            count($productViewTransfer->getAttributeMap()->getSuperAttributes()) === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function getSelectedVariantNode(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getAttributeMap()) {
            return [];
        }

        if ($productViewTransfer->getAttributeMap()->getAttributeVariantCollection()) {
            return $this->buildAttributeMapByAttributeVariantCollection(
                $productViewTransfer->getSelectedAttributes(),
                $productViewTransfer->getAttributeMap()->getAttributeVariantCollection()
            );
        }

        return $this->buildAttributeMapFromSelected(
            $productViewTransfer->getSelectedAttributes(),
            $productViewTransfer->getAttributeMap()->getAttributeVariants()
        );
    }

    /**
     * @param array $selectedVariantNode
     * @param \Generated\Shared\Transfer\ProductViewTransfer $storageProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function setAvailableAttributes(array $selectedVariantNode, ProductViewTransfer $storageProductTransfer)
    {
        if ($storageProductTransfer->getAttributeMap()->getAttributeVariantCollection()) {
            return $storageProductTransfer->setAvailableAttributes($this->getAvailableAttributes($storageProductTransfer));
        }

        $storageProductTransfer->setAvailableAttributes($this->findAvailableAttributes($selectedVariantNode));

        return $storageProductTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param array $selectedAttributes
     * @param array $attributeVariants
     *
     * @return array
     */
    protected function buildAttributeMapFromSelected(array $selectedAttributes, array $attributeVariants)
    {
        ksort($selectedAttributes);

        $attributePath = $this->buildAttributePath($selectedAttributes);

        return $this->findSelectedNode($attributeVariants, $attributePath);
    }

    /**
     * @param array $selectedNode
     * @param array $filteredAttributes
     *
     * @return array
     */
    protected function findAvailableAttributes(array $selectedNode, array $filteredAttributes = [])
    {
        foreach (array_keys($selectedNode) as $attributePath) {
            [$key, $value] = explode(ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $attributePath);
            $filteredAttributes[$key][] = $value;
        }

        return $filteredAttributes;
    }

    /**
     * @param array $attributeMap
     * @param array $selectedAttributes
     * @param array $selectedNode
     *
     * @return array
     */
    protected function findSelectedNode(array $attributeMap, array $selectedAttributes, array $selectedNode = [])
    {
        $selectedKey = array_shift($selectedAttributes);
        foreach ($attributeMap as $variantKey => $variant) {
            if ($variantKey !== $selectedKey) {
                continue;
            }

            return $this->findSelectedNode($variant, $selectedAttributes, $variant);
        }

        if (count($selectedAttributes) > 0) {
            return $this->findSelectedNode($attributeMap, $selectedAttributes, $selectedNode);
        }

        return $selectedNode;
    }

    /**
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function buildAttributePath(array $selectedAttributes)
    {
        $attributePath = [];
        foreach ($selectedAttributes as $attributeName => $attributeValue) {
            if (!$attributeValue) {
                continue;
            }

            $attributePath[] = $attributeName . ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER . $attributeValue;
        }

        return $attributePath;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getFirstProductVariant(ProductViewTransfer $productViewTransfer, $locale)
    {
        $productConcreteIds = $productViewTransfer->getAttributeMap()->getProductConcreteIds();
        $idProductConcrete = array_shift($productConcreteIds);
        $productConcreteStorageData = $this->productConcreteStorageReader->findProductConcreteStorageData(
            $idProductConcrete,
            $locale
        );
        $productViewTransfer->getAttributeMap()->setSuperAttributes([]);

        if (!$productConcreteStorageData) {
            return $productViewTransfer;
        }

        return $this->mergeAbstractAndConcreteProducts($productViewTransfer, $productConcreteStorageData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productConcreteStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function mergeAbstractAndConcreteProducts(
        ProductViewTransfer $productViewTransfer,
        array $productConcreteStorageData
    ) {
        $productConcreteStorageData = array_filter($productConcreteStorageData, function ($value) {
            return $value !== null;
        });

        $productViewTransfer->fromArray($productConcreteStorageData, true);

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     * @param array $selectedVariantNode
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getSelectedProductVariant(
        ProductViewTransfer $productViewTransfer,
        $locale,
        array $selectedVariantNode
    ): ProductViewTransfer {
        if (!$this->isProductConcreteNodeReached($selectedVariantNode)) {
            return $productViewTransfer;
        }

        $idProductConcrete = $this->extractIdOfProductConcrete($selectedVariantNode);
        $productViewTransfer->setIdProductConcrete($idProductConcrete);
        $productConcreteStorageData = $this->productConcreteStorageReader->findProductConcreteStorageData($idProductConcrete, $locale);

        if (!$productConcreteStorageData) {
            return $productViewTransfer;
        }

        return $this->mergeAbstractAndConcreteProducts($productViewTransfer, $productConcreteStorageData);
    }

    /**
     * @param array $selectedVariantNode
     *
     * @return bool
     */
    protected function isProductConcreteNodeReached(array $selectedVariantNode)
    {
        return isset($selectedVariantNode[ProductConfig::VARIANT_LEAF_NODE_ID]);
    }

    /**
     * @param array $selectedVariantNode
     *
     * @return int
     */
    protected function extractIdOfProductConcrete(array $selectedVariantNode)
    {
        if (is_array($selectedVariantNode[ProductConfig::VARIANT_LEAF_NODE_ID])) {
            return array_shift($selectedVariantNode[ProductConfig::VARIANT_LEAF_NODE_ID]);
        }

        return $selectedVariantNode[ProductConfig::VARIANT_LEAF_NODE_ID];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function setSingleValueAttributesAsSelected(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $originalSelectedAttributes = $productViewTransfer->getSelectedAttributes();

        $superAttributes = $productViewTransfer->getAttributeMap()->getSuperAttributes();

        $autoSelectedSingleValueSuperAttributes = [];

        foreach ($superAttributes as $superAttributeName => $superAttributeValues) {
            if (count($superAttributeValues) === 1) {
                $autoSelectedSingleValueSuperAttributes[$superAttributeName] = $superAttributeValues[0];
            }
        }

        $productViewTransfer->setSelectedAttributes($autoSelectedSingleValueSuperAttributes + $originalSelectedAttributes);

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function getAvailableAttributes(ProductViewTransfer $productViewTransfer): array
    {
        $availableAttributes = [];
        $selectedAttributes = $this->sanitizeEmptySelectedAttributes($productViewTransfer);

        if (!$selectedAttributes) {
            return [];
        }

        $attributeVariantCollection = $productViewTransfer->getAttributeMap()->getAttributeVariantCollection();

        foreach ($attributeVariantCollection as $idProductConcrete => $productSuperAttributes) {
            if (!$this->isSubsetAttributes($selectedAttributes, $productSuperAttributes)) {
                continue;
            }

            $availableAttributes = $this->filterAvailableAttributes(
                $productSuperAttributes,
                $selectedAttributes,
                $availableAttributes
            );
        }

        return $availableAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function sanitizeEmptySelectedAttributes(ProductViewTransfer $productViewTransfer): array
    {
        return array_filter($productViewTransfer->getSelectedAttributes(), function ($attribute) {
            return $attribute;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param array $selectedAttributes
     * @param array $attributeVariantCollection
     *
     * @return array
     */
    protected function buildAttributeMapByAttributeVariantCollection(
        array $selectedAttributes,
        array $attributeVariantCollection
    ): array {
        foreach ($attributeVariantCollection as $idProductConcrete => $productSuperAttributes) {
            if ($selectedAttributes != $productSuperAttributes) {
                continue;
            }

            return [ProductConfig::VARIANT_LEAF_NODE_ID => $idProductConcrete];
        }

        return [];
    }

    /**
     * @param array $superAttributes
     * @param array $superAttributeHaystack
     *
     * @return bool
     */
    protected function isSubsetAttributes(array $superAttributes, array $superAttributeHaystack): bool
    {
        foreach ($superAttributes as $superAttributeKey => $superAttributeValue) {
            if (!$this->includeSameAttribute($superAttributeHaystack, $superAttributeKey, $superAttributeValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $superAttributeHaystack
     * @param string $superAttributeKey
     * @param string $superAttributeValue
     *
     * @return bool
     */
    protected function includeSameAttribute(
        array $superAttributeHaystack,
        string $superAttributeKey,
        string $superAttributeValue
    ): bool {
        return isset($superAttributeHaystack[$superAttributeKey]) && $superAttributeHaystack[$superAttributeKey] === $superAttributeValue;
    }

    /**
     * @param array $superAttributes
     * @param array $selectedAttributes
     * @param array $availableAttributes
     *
     * @return array
     */
    protected function filterAvailableAttributes(
        array $superAttributes,
        array $selectedAttributes,
        array $availableAttributes
    ): array {
        foreach (array_diff_assoc($superAttributes, $selectedAttributes) as $attributeKey => $attributeValue) {
            if (isset($availableAttributes[$attributeKey]) && in_array($attributeValue, $availableAttributes[$attributeKey])) {
                continue;
            }

            $availableAttributes[$attributeKey][] = $attributeValue;
        }

        return $availableAttributes;
    }
}
