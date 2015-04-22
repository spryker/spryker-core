<?php

namespace SprykerFeature\Zed\ProductSearch\Business\Transformer;

/**
 * Interface ProductAttributesTransformerInterface
 *
 * @package SprykerFeature\Zed\ProductSearch\Business\Tranformer
 */
interface ProductAttributesTransformerInterface
{
    /**
     * @param array  $productsRaw
     * @param array  $searchableProducts
     *
     * @return array
     */
    public function buildProductAttributes(array $productsRaw, array $searchableProducts);
}