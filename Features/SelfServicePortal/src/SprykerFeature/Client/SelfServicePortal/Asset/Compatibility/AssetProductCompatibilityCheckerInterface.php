<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Asset\Compatibility;

interface AssetProductCompatibilityCheckerInterface
{
    /**
     * Specification:
     * - Checks asset compatibility with products by SKUs in bulk.
     * - Returns an array indexed by asset reference and SKU combinations.
     * - Each result indicates whether the asset is compatible with the product.
     * - Uses ProductStorageClient to resolve SKUs to product IDs.
     * - Uses current locale for product data retrieval.
     *
     * @param array<string> $assetReferences
     * @param array<string> $skus
     *
     * @return array<string, array<string, bool>> Indexed by [assetReference][sku] => bool
     */
    public function getAssetProductCompatibilityMatrix(array $assetReferences, array $skus): array;

    /**
     * Specification:
     * - Checks asset compatibility with a single product by SKU.
     * - Returns true if the asset is compatible with the product, false otherwise.
     * - Uses ProductStorageClient to resolve SKU to product ID.
     * - Uses current locale for product data retrieval.
     *
     * @param string $assetReference
     * @param string $sku
     *
     * @return bool
     */
    public function isAssetCompatibleToProductSku(string $assetReference, string $sku): bool;
}
