<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Filter;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToUtilSanitizeServiceInterface;

class ProductAttributeFilter implements ProductAttributeFilterInterface
{
    /**
     * @uses \Spryker\Zed\Product\ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER
     */
    protected const ATTRIBUTE_MAP_PATH_DELIMITER = ':';

    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToUtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct(ProductStorageToUtilSanitizeServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @param array $selectedVariantNode
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    public function filterAvailableProductAttributes(
        array $selectedVariantNode,
        ProductViewTransfer $productViewTransfer
    ): array {
        if ($productViewTransfer->getAttributeMap()->getAttributeVariantMap()) {
            return $this->getAvailableAttributes($productViewTransfer);
        }

        return $this->findAvailableAttributes($selectedVariantNode);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function getAvailableAttributes(ProductViewTransfer $productViewTransfer): array
    {
        $availableAttributes = [];
        $selectedAttributes = $this->utilSanitizeService->arrayFilterRecursive($productViewTransfer->getSelectedAttributes());

        if (!$selectedAttributes) {
            return [];
        }

        $attributeVariantMap = $productViewTransfer->getAttributeMap()->getAttributeVariantMap();

        foreach ($attributeVariantMap as $idProductConcrete => $productSuperAttributes) {
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
     * @deprecated Exists for Backward Compatibility reasons only. Use {@link getAvailableAttributes()} instead.
     *
     * @param array $selectedNode
     * @param array $filteredAttributes
     *
     * @return array
     */
    protected function findAvailableAttributes(array $selectedNode, array $filteredAttributes = [])
    {
        foreach (array_keys($selectedNode) as $attributePath) {
            [$attributeKey, $attributeValue] = explode(static::ATTRIBUTE_MAP_PATH_DELIMITER, $attributePath);
            $filteredAttributes[$attributeKey][] = $attributeValue;
        }

        return $filteredAttributes;
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
        $attributesToAdd = array_diff_assoc($superAttributes, $selectedAttributes);

        foreach ($attributesToAdd as $attributeKey => $attributeValue) {
            if ($this->hasAttributeWithValue($availableAttributes, $attributeKey, $attributeValue)) {
                continue;
            }

            $availableAttributes[$attributeKey][] = $attributeValue;
        }

        return $availableAttributes;
    }

    /**
     * @param array $selectedAttributes
     * @param array $productSuperAttributes
     *
     * @return bool
     */
    protected function isSubsetAttributes(array $selectedAttributes, array $productSuperAttributes): bool
    {
        foreach ($selectedAttributes as $superAttributeKey => $superAttributeValue) {
            if (!$this->includeSameAttribute($productSuperAttributes, $superAttributeKey, $superAttributeValue)) {
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
     * @param array $availableAttributes
     * @param string $attributeKey
     * @param string $attributeValue
     *
     * @return bool
     */
    protected function hasAttributeWithValue(array $availableAttributes, string $attributeKey, string $attributeValue): bool
    {
        return isset($availableAttributes[$attributeKey]) && in_array($attributeValue, $availableAttributes[$attributeKey], true);
    }
}
