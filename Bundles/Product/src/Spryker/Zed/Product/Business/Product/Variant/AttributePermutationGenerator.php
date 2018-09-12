<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Variant;

use Spryker\Shared\Product\ProductConfig;

class AttributePermutationGenerator implements AttributePermutationGeneratorInterface
{
    /**
     * Generatate all possible permutations for given attribute.
     * Leaf node of a tree is concrete id.
     * (
     *   [color:red] => array (
     *       [brand:nike] => array(
     *          [id] => 1
     *       )
     *   ),
     *   [brand:nike] => array(
     *       [color:red] => array(
     *          [id] => 1
     *       )
     *   )
     * )
     *
     * @param array $superAttributes
     * @param int $idProductConcrete
     * @param array $variants
     *
     * @return array
     */
    public function generateAttributePermutations(array $superAttributes, $idProductConcrete, array $variants = [])
    {
        if (!$superAttributes) {
            return [
                ProductConfig::VARIANT_LEAF_NODE_ID => $idProductConcrete, // Set leaf node to id of concrete product
            ];
        }

        $result = [];

        $index = 0;
        foreach ($superAttributes as $key => $value) {
            $newAttributes = $superAttributes;
            $newVariants = $variants;

            $newVariants[] = array_splice($newAttributes, $index++, 1);

            $recurseResult = $this->generateAttributePermutations($newAttributes, $idProductConcrete, $newVariants);
            if (is_array($recurseResult)) {
                $recurseResult = array_merge($result, $recurseResult);
            }

            $result[$key . ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER . $value] = $recurseResult;
        }

        return $result;
    }
}
