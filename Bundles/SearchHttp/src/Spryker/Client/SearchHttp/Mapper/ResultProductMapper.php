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
                'abstract_name' => $product['name'], # This is only one way to have abstract_name
                'abstract_sku' => $product['product_abstract_sku'],
                'url' => $product['url'],
                'images' => $this->mapSearchHttpImagesToOriginalImages($product['images']),
                'prices' => $product['prices'],
                'id_product_labels' => [],
            ];
        }

        return $result;
    }

    /**
     * @param array<int, string> $images
     *
     * @return array<int, mixed>
     */
    protected function mapSearchHttpImagesToOriginalImages(array $images): array
    {
        // TODO: needs to be updated later, because it's not fully compatible with original search result from elasticsearch.

        $result = [];

        foreach ($images as $image) {
            $result[] = [
                'external_url_small' => $image,
                'external_url_large' => $image,
            ];
        }

        return $result;
    }
}
