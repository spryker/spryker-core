<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductConfigurationStorageClientInterface
{
    /**
     * Specification:
     * - Reads product configuration instance from session first then from storage if not found.
     * - Returns null if configuration instance is not found neither sources.
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationInstanceTransfer;

    /**
     * Specification:
     * - Stores ProductConfigurationInstanceTransfer in the session by SKU.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return void
     */
    public function storeProductConfigurationInstanceBySku(
        string $sku,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): void;

    /**
     * Specification:
     * - Expands the product view with the product configuration data.
     * - Expects ProductViewTransfer::sku property to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductConfiguration(ProductViewTransfer $productViewTransfer): ProductViewTransfer;

    /**
     * Specification:
     * - Retrieves current store.
     * - Retrieves product concrete storage data by id and storage.
     * - Retrieves product configuration instance by product concrete sku.
     * - Returns product configuration prices or empty array if prices weren't set.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesByIdProductConcrete(int $idProductConcrete): array;



    /**
     * Specification:
     * - Retrieves product configuration from product view.
     * - Expands price product filter with product configuration instance.
     * - Returns expanded price product filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function expandPriceProductFilterWithProductConfiguration(
        ProductViewTransfer $productViewTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductFilterTransfer;

    /**
     * Specification:
     * - Checks product view for the product configuration instance existence.
     * - Returns true if exist.
     * - Makes attempt to find it by sku.
     * - Returns true if found or false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductViewTransferHasProductConfigurationInstance(ProductViewTransfer $productViewTransfer): bool;

    /**
     * Specification:
     * - Returns true if product concrete configuration is available for given product view transfer or false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductConcreteAvailable(ProductViewTransfer $productViewTransfer): bool;
}
