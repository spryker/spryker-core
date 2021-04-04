<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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
     * - Finds the appropriate item in the quote by groupKey and SKU.
     * - Returns the found item's ProductConfigurationInstanceTransfer when available.
     * - Returns NULL otherwise.
     *
     * @api
     *
     * @param string $groupKey
     * @param string $sku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceInQuote(
        string $groupKey,
        string $sku,
        QuoteTransfer $quoteTransfer
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
     * @param array $productData
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        string $localeName,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer;

    /**
     * Specification:
     * - Retrieves current store specific product concrete storage data by id.
     * - Retrieves product configuration instance by product concrete SKU.
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
     * - Retrieves product configuration instance from product view.
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
    public function expandPriceProductFilterWithProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductFilterTransfer;

    /**
     * Specification:
     * - Checks product view for the product configuration instance existence.
     * - Returns true if exist.
     * - Makes attempt to find it by SKU.
     * - Returns true if found, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductHasProductConfigurationInstance(ProductViewTransfer $productViewTransfer): bool;

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

    /**
     * Specification:
     * - Expands the provided cart change transfer items with the corresponding product configuration instance.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithProductConfigurationInstance(
        CartChangeTransfer $cartChangeTransfer,
        array $params
    ): CartChangeTransfer;

    /**
     * Specification:
     * - Expands the provided persistent cart change transfer items with the corresponding product configuration instance.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params
    ): PersistentCartChangeTransfer;

    /**
     * Specification:
     * - Validates response trough validators stack.
     * - Saves product configuration instance to the session storage when source type is pdp.
     * - Adjusts cart item quantity according to product configuration quantity restrictions.
     * - Replaces quote item product configuration with new one when source type is cart page.
     * - Returns `isSuccessful=true` on success or `isSuccessful=false` with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer;

    /**
     * Specification:
     * - Extracts additional product configuration volume prices from price product data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductConfigurationVolumePrices(array $priceProductTransfers): array;
}
