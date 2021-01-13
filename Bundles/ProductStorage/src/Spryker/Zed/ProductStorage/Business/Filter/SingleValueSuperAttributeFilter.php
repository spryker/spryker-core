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
        $filteredProductConcreteSuperAttributeMap = $productConcreteSuperAttributeMap;

        foreach ($productConcreteSuperAttributeMap as $productId => $attributes) {
            foreach ($attributes as $attributeName => $attributeValue) {
                if (!$this->isSingleValueSuperAttribute($superAttributeVariations, $attributeName)) {
                    continue;
                }

                unset($filteredProductConcreteSuperAttributeMap[$productId][$attributeName]);
            }
        }

        return $filteredProductConcreteSuperAttributeMap;
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
            && count($superAttributeVariations[$attributeName]) === self::SINGLE_VALUE_ATTRIBUTE_COUNT;
    }
}
