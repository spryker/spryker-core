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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductVariantData(ProductViewTransfer $productViewTransfer, $locale)
    {
        $productViewTransfer->requireAttributeMap();

        if (count($productViewTransfer->getAttributeMap()->getProductConcreteIds()) === 1 ||
            count($productViewTransfer->getAttributeMap()->getSuperAttributes()) === 0
        ) {
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
     *
     * @return array
     */
    protected function getSelectedVariantNode(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getAttributeMap()) {
            return [];
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
        $storageProductTransfer->setAvailableAttributes($this->findAvailableAttributes($selectedVariantNode));

        return $storageProductTransfer;
    }

    /**
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
            list($key, $value) = explode(ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER, $attributePath);
            $filteredAttributes[$key][] = $value;
            if (is_array($value)) {
                return $this->findAvailableAttributes($value, $filteredAttributes);
            }
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
        $productConcreteStorageData = $this->productConcreteStorageReader->findProductConcreteStorageData($idProductConcrete, $locale);

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
    protected function mergeAbstractAndConcreteProducts(ProductViewTransfer $productViewTransfer, array $productConcreteStorageData)
    {
        $productConcreteStorageData = array_filter($productConcreteStorageData);
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
    protected function getSelectedProductVariant(ProductViewTransfer $productViewTransfer, $locale, array $selectedVariantNode)
    {
        if (!$this->isProductConcreteNodeReached($selectedVariantNode)) {
            return $productViewTransfer;
        }

        $idProductConcrete = $this->extractIdOfProductConcrete($selectedVariantNode);
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
}
