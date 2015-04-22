<?php

namespace SprykerFeature\Zed\Product\Business\Builder;


/**
 * Class SimpleAttributeMergeBuilder
 *
 * @package SprykerFeature\Zed\Product\Business\Builder
 */
class SimpleAttributeMergeBuilder
{
    /**
     * @param array $productsData
     *
     * @return array
     */
    public function buildProducts(array $productsData)
    {
        foreach ($productsData as &$productData) {
            $abstractAttributes = json_decode($productData['abstract_attributes'], true);
            $concreteAttributes = json_decode($productData['attributes'], true);
            $attributes = array_merge($abstractAttributes, $concreteAttributes);

            unset($productData['abstract_attributes']);

            $productData['attributes'] = $this->normalizeAttributes($attributes);
        }

        return $productsData;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function normalizeAttributes(array $attributes)
    {
        $newKeys = array_map(function ($name) {
            return str_replace(' ', '', lcfirst(ucwords($name)));
        }, array_keys($attributes));

        return array_combine($newKeys, $attributes);
    }
}
