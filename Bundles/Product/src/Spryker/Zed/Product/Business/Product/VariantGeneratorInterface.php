<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface VariantGeneratorInterface
{

    /**
     * @param array $tokenAttributeCollection
     *
     * @return array
     */
    public function generateTokens(array $tokenAttributeCollection);

    /**
     * $attributeCollection = Array
     *  (
     *     [color] => Array
     *      (
     *          [red] => Red
     *          [blue] => Blue
     *      )
     *     [flavour] => Array
     *      (
     *          [sweet] => Cakes
     *      )
     *     [size] => Array
     *      (
     *          [40] => 40
     *          [41] => 41
     *          [42] => 42
     *          )
     *      )
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array|\Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function generate(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection);

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
