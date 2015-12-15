<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Business\Transformer;

/**
 * Interface ProductAttributesTransformerInterface
 */

interface ProductAttributesTransformerInterface
{

    /**
     * @param array $productsRaw
     * @param array $searchableProducts
     *
     * @return array
     */
    public function buildProductAttributes(array $productsRaw, array $searchableProducts);

}
