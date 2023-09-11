<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Mapper;

class ResultProductMapper implements ResultProductMapperInterface
{
    /**
     * @param array<int, mixed> $products
     *
     * @return array<int, mixed>
     */
    public function mapSearchHttpProductsToOriginalProducts(array $products): array
    {
        $result = [];
        foreach ($products as $key => $product) {
            $result[$key] = array_merge($product, [
                'type' => 'product_abstract',
                'abstract_sku' => $product['product_abstract_sku'],
                'images' => $this->getImagesFromImageSet($product['images']),
                'add_to_cart_sku' => $product['sku'],
                'labels' => $product['label'],
                'id_product_labels' => [],
            ]);
            unset($result[$key]['sku'], $result[$key]['label']);
        }

        return $result;
    }

    /**
     * @param array<string, array<int, array<string, string>>> $images
     *
     * @return array<int, mixed>
     */
    protected function getImagesFromImageSet(array $images): array
    {
        $result = [];

        if (!count($images)) {
            return $result;
        }

        if (isset($images['default'])) {
            return $this->mapSearchHttpImagesToOriginalImages($images['default']);
        }

        return $this->mapSearchHttpImagesToOriginalImages(current($images));
    }

    /**
     * @param array<int, array<string, string>> $images
     *
     * @return array<int, mixed>
     */
    protected function mapSearchHttpImagesToOriginalImages(array $images): array
    {
        // TODO: needs to be updated later, because it's not fully compatible with original search result from elasticsearch.

        $result = [];

        foreach ($images as $image) {
            $result[] = [
                'external_url_small' => $image['small'],
                'external_url_large' => $image['large'],
            ];
        }

        return $result;
    }
}
