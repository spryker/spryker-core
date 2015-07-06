<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
            $productUrls = explode(',', $productData['product_urls']);
            $productData['url'] = $productUrls[0];

            $productData['abstract_attributes'] = $this->extractAbstractAttributes($productData);

            $concreteAttributes = explode('$%', $productData['concrete_attributes']);
            $concreteLocalizedAttributes = explode('$%', $productData['concrete_localized_attributes']);

            $concreteSkus = explode(',', $productData['concrete_skus']);
            $concreteNames = explode(',', $productData['concrete_names']);
            $productData['concrete_products'] = [];

            $processedConcreteSkus = [];
            for ($i = 0, $l = count($concreteSkus); $i < $l; $i++) {
                if (isset($processedConcreteSkus[$concreteSkus[$i]])) {
                    continue;
                }
                $decodedAttributes = json_decode($concreteAttributes[$i], true);
                $decodedLocalizedAttributes = json_decode($concreteLocalizedAttributes[$i], true);
                $mergedAttributes = array_merge($decodedAttributes, $decodedLocalizedAttributes);

                $processedConcreteSkus[$concreteSkus[$i]] = true;
                $productData['concrete_products'][] = [
                    'name' => $concreteNames[$i],
                    'sku' => $concreteSkus[$i],
                    'attributes' => $mergedAttributes,
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

    /**
     * @param array $productData
     *
     * @return array
     */
    protected function extractAbstractAttributes(array $productData)
    {
        $decodedAttributes = json_decode($productData['abstract_attributes'], true);
        $decodedLocalizedAttributes = json_decode($productData['abstract_localized_attributes'], true);
        $abstractAttributes = array_merge($decodedAttributes, $decodedLocalizedAttributes);

         return $this->normalizeAttributes($abstractAttributes);
    }

    /**
     * @param array $productData
     *
     * @return array
     */
    protected function extractAbstractAttributes(array $productData)
    {
        $decodedAttributes = json_decode($productData['abstract_attributes'], true);
        $decodedLocalizedAttributes = json_decode($productData['abstract_localized_attributes'], true);
        $abstractAttributes = array_merge($decodedAttributes, $decodedLocalizedAttributes);

         return $this->normalizeAttributes($abstractAttributes);
    }

    /**
     * @param array $productData
     *
     * @return array
     */
    protected function extractAbstractAttributes(array $productData)
    {
        $decodedAttributes = json_decode($productData['abstract_attributes'], true);
        $decodedLocalizedAttributes = json_decode($productData['abstract_localized_attributes'], true);
        $abstractAttributes = array_merge($decodedAttributes, $decodedLocalizedAttributes);

         return $this->normalizeAttributes($abstractAttributes);
    }

}
