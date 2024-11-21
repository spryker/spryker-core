<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Mapper;

use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;

class ResultProductMapper implements ResultProductMapperInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_SEARCH_QUERY_ID_PARAM = 'search-id';

    /**
     * @var string
     */
    protected const PRODUCT_SEARCH_QUERY_ID = 'searchQueryId';

    /**
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer|\Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchHttpResponseTransfer
     * @param array<int, mixed> $products
     *
     * @return array<int, mixed>
     */
    public function mapSearchHttpProductsToOriginalProducts(
        SearchHttpResponseTransfer|SuggestionsSearchHttpResponseTransfer $searchHttpResponseTransfer,
        array $products
    ): array {
        $result = [];
        $curentPosition = 0;
        foreach ($products as $key => $product) {
            $curentPosition++;
            $result[$key] = array_merge($product, [
                'type' => 'product_abstract',
                'abstract_sku' => $product['product_abstract_sku'],
                'images' => $this->getImagesFromImageSet($product['images']),
                'add_to_cart_sku' => $product['sku'],
                'labels' => $product['label'],
                'id_product_labels' => [],
            ]);

            if ($searchHttpResponseTransfer instanceof SearchHttpResponseTransfer && $searchHttpResponseTransfer->getQueryId()) {
                $result[$key][static::PRODUCT_SEARCH_QUERY_ID] = $searchHttpResponseTransfer->getQueryId();
                $result[$key]['url'] = $this->expandProductUrlWithQueryId($product['url'], $searchHttpResponseTransfer->getQueryId());
            }

            if ($searchHttpResponseTransfer instanceof SearchHttpResponseTransfer && $searchHttpResponseTransfer->getPagination()) {
                $result[$key]['position'] = $searchHttpResponseTransfer->getPagination()->getCurrentPage()
                    * $searchHttpResponseTransfer->getPagination()->getCurrentItemsPerPage()
                    - $searchHttpResponseTransfer->getPagination()->getCurrentItemsPerPage()
                    + $curentPosition;
            }

            unset($result[$key]['sku'], $result[$key]['label']);
        }

        return $result;
    }

    /**
     * @param string $productUrl
     * @param string|null $queryId
     *
     * @return string
     */
    protected function expandProductUrlWithQueryId(string $productUrl, ?string $queryId): string
    {
        if (!$queryId) {
            return $productUrl;
        }

        $separator = strpos($productUrl, '?') === false ? '?' : '&';

        return $productUrl . $separator . http_build_query([static::PRODUCT_SEARCH_QUERY_ID_PARAM => $queryId]);
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
