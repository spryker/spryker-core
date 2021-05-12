<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Filter;

class SingleValueSuperAttributeFilter implements SingleValueSuperAttributeFilterInterface
{
    protected const SINGLE_VALUE_ATTRIBUTE_COUNT = 1;

    /**
     * @param string[][] $productConcreteSuperAttributeMap
     * @param string[][] $superAttributeVariations
     *
     * @return string[][]
     */
    public function filterOutSingleValueSuperAttributes(
        array $productConcreteSuperAttributeMap,
        array $superAttributeVariations
    ): array {
        $filteredProductConcreteSuperAttributeMap = [];
        foreach ($productConcreteSuperAttributeMap as $productId => $attributes) {
            $filteredSuperAttributes = $this->filterSingleValueSuperAttributes(
                $attributes,
                $superAttributeVariations
            );

            $filteredProductConcreteSuperAttributeMap[$productId] = $filteredSuperAttributes;
        }

        return $filteredProductConcreteSuperAttributeMap;
    }

    /**
     * @param string[] $attributes
     * @param string[][] $superAttributeVariations
     *
     * @return string[]
     */
    protected function filterSingleValueSuperAttributes(
        array $attributes,
        array $superAttributeVariations
    ): array {
        $filteredSuperAttributes = [];
        foreach ($attributes as $attributeName => $attributeValue) {
            if ($this->isSingleValueSuperAttribute($superAttributeVariations, $attributeName)) {
                continue;
            }

            $filteredSuperAttributes[$attributeName] = $attributeValue;
        }

        return $filteredSuperAttributes;
    }

    /**
     * @param string[][] $superAttributeVariations
     * @param string $attributeName
     *
     * @return bool
     */
    protected function isSingleValueSuperAttribute(array $superAttributeVariations, string $attributeName): bool
    {
        return isset($superAttributeVariations[$attributeName])
            && count($superAttributeVariations[$attributeName]) === static::SINGLE_VALUE_ATTRIBUTE_COUNT;
    }
}
