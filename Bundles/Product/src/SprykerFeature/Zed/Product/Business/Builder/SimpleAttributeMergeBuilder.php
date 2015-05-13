<?php

namespace SprykerFeature\Zed\Product\Business\Builder;

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
            $productData['abstract_attributes'] = $this->normalizeAttributes($abstractAttributes);
            $concreteAttributes = explode('$%', $productData['concrete_attributes']);
            $concreteSkus = explode(',', $productData['concrete_skus']);
            $concreteNames = explode(',', $productData['concrete_names']);
            $productData['concrete_products'] = [];

            $lastSku = '';
            for ($i = 0, $l = count($concreteSkus); $i < $l; $i++) {
                if ($lastSku === $concreteSkus[$i]) {
                    continue;
                }

                $lastSku = $concreteSkus[$i];
                $productData['concrete_products'][] = [
                    'name' => $concreteNames[$i],
                    'sku' => $concreteSkus[$i],
                    'attributes' => json_decode($concreteAttributes[$i], true)
                ];
            }
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
