<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
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
        // TODO: needs to be updated later, because it's not fully compatible with original search result from elasticsearch.

        $result = [];

        foreach ($products as $product) {
            $result[] = [
                'id_product_abstract' => $product['id_product_abstract'],
                'type' => 'product_abstract',
                'abstract_name' => $product['abstract_name'],
                'abstract_sku' => $product['product_abstract_sku'],
                'url' => $product['url'],
                'images' => $this->getImagesFromImageSet($product['images']),
                'prices' => $product['prices'],
                'id_product_labels' => [],
            ];
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
