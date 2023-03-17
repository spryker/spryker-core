<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class ProductSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface
{
    /**
     * @var string
     */
    protected const SKU_KEY_PRODUCT_ABSTRACT = 'product_abstract_sku';

    /**
     * @var string
     */
    protected const FIELD_NAME_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    protected const PRODUCT_MAPPING_TYPE = 'sku';

    /**
     * @var string
     */
    protected const PRODUCT_COLLECTION_KEY = 'items';

    /**
     * @var string
     */
    public const NAME = 'products';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     * - Formats products in result.
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<int, mixed>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        $products = $searchResult[static::PRODUCT_COLLECTION_KEY];

        $products = $this->extendWithProductIds($products);
        $products = $this->filterNotFoundInAbstractProducts($products);
        $products = $this->getFactory()->createResultProductMapper()->mapSearchHttpProductsToOriginalProducts($products);

        return $products;
    }

    /**
     * @param array<int, mixed> $products
     *
     * @return array<int, mixed>
     */
    protected function extendWithProductIds(array $products): array
    {
        $localName = $this->getFactory()->getLocaleClient()->getCurrentLocale();

        $skus = $this->extractSkusFromProducts($products, static::SKU_KEY_PRODUCT_ABSTRACT);

        $skusToIds = $this->getFactory()->getProductStorageClient()->getBulkProductAbstractIdsByMapping(
            static::PRODUCT_MAPPING_TYPE,
            $skus,
            $localName,
        );

        return $this->bindProductIdsToProductsBySku(
            $products,
            $skusToIds,
            static::SKU_KEY_PRODUCT_ABSTRACT,
            static::FIELD_NAME_ID_PRODUCT_ABSTRACT,
        );
    }

    /**
     * @param array<int, mixed> $products
     * @param string $skuKey
     *
     * @return array<int, mixed>
     */
    protected function extractSkusFromProducts(array $products, string $skuKey): array
    {
        $abstractSkus = [];

        foreach ($products as $product) {
            $abstractSkus[] = $product[$skuKey];
        }

        return $abstractSkus;
    }

    /**
     * @param array<int, mixed> $products
     * @param array<string, int> $productSkusToIds
     * @param string $skuKey
     * @param string $idKey
     *
     * @return array<int, mixed>
     */
    protected function bindProductIdsToProductsBySku(
        array $products,
        array $productSkusToIds,
        string $skuKey,
        string $idKey
    ): array {
        foreach ($products as $key => $product) {
            if (isset($productSkusToIds[$product[$skuKey]])) {
                $products[$key][$idKey] = $productSkusToIds[$product[$skuKey]];
            }
        }

        return $products;
    }

    /**
     * @param array<int, mixed> $products
     *
     * @return array<int, mixed>
     */
    protected function filterNotFoundInAbstractProducts(array $products): array
    {
        $result = [];

        foreach ($products as $product) {
            if (isset($product[static::FIELD_NAME_ID_PRODUCT_ABSTRACT])) {
                $result[] = $product;
            }
        }

        return $result;
    }
}
