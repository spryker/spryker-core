<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Builder;

class SimpleAttributeMergeBuilder
{

    const PRODUCT_URLS = 'product_urls';
    const URL = 'url';
    const ABSTRACT_ATTRIBUTES = 'abstract_attributes';
    const CONCRETE_ATTRIBUTES = 'concrete_attributes';
    const CONCRETE_LOCALIZED_ATTRIBUTES = 'concrete_localized_attributes';
    const CONCRETE_SKUS = 'concrete_skus';
    const CONCRETE_NAMES = 'concrete_names';
    const CONCRETE_PRODUCTS = 'concrete_products';
    const NAME = 'name';
    const SKU = 'sku';
    const ATTRIBUTES = 'attributes';
    const ABSTRACT_LOCALIZED_ATTRIBUTES = 'abstract_localized_attributes';

    /**
     * @param array $productsData
     *
     * @return array
     */
    public function buildProducts(array $productsData)
    {
        foreach ($productsData as &$productData) {
            $productUrls = explode(',', $productData[self::PRODUCT_URLS]);
            $productData[self::URL] = $productUrls[0];

            $productData[self::ABSTRACT_ATTRIBUTES] = $this->extractAbstractAttributes($productData);

            $concreteAttributes = explode('$%', $productData[self::CONCRETE_ATTRIBUTES]);
            $concreteLocalizedAttributes = explode('$%', $productData[self::CONCRETE_LOCALIZED_ATTRIBUTES]);

            $concreteSkus = explode(',', $productData[self::CONCRETE_SKUS]);
            $concreteNames = explode(',', $productData[self::CONCRETE_NAMES]);
            $productData[self::CONCRETE_PRODUCTS] = [];

            $processedConcreteSkus = [];
            for ($i = 0, $l = count($concreteSkus); $i < $l; $i++) {
                if (isset($processedConcreteSkus[$concreteSkus[$i]])) {
                    continue;
                }
                $decodedAttributes = json_decode($concreteAttributes[$i], true);
                $decodedLocalizedAttributes = json_decode($concreteLocalizedAttributes[$i], true);
                if (is_null($decodedLocalizedAttributes)) {
                    $decodedLocalizedAttributes = [];
                }
                $mergedAttributes = array_merge($decodedAttributes, $decodedLocalizedAttributes);

                $processedConcreteSkus[$concreteSkus[$i]] = true;
                $productData[self::CONCRETE_PRODUCTS][] = [
                    self::NAME => $concreteNames[$i],
                    self::SKU => $concreteSkus[$i],
                    self::ATTRIBUTES => $mergedAttributes,
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
        $decodedAttributes = json_decode($productData[self::ABSTRACT_ATTRIBUTES], true);
        $decodedLocalizedAttributes = json_decode($productData[self::ABSTRACT_LOCALIZED_ATTRIBUTES], true);
        $abstractAttributes = array_merge($decodedAttributes, $decodedLocalizedAttributes);

         return $this->normalizeAttributes($abstractAttributes);
    }

}
