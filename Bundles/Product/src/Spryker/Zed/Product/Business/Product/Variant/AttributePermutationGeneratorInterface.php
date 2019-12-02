<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Variant;

interface AttributePermutationGeneratorInterface
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
    public function generateAttributePermutations(array $superAttributes, $idProductConcrete, array $variants = []);
}
