<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCollectionResponseTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface PriceProductFacadeInterface
{
    /**
     * Specification:
     * - Reads all persisted price types and returns their names in an array.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\PriceTypeTransfer>
     */
    public function getPriceTypeValues();

    /**
     * Specification:
     * - Searches for a persisted price in database that has the given SKU for the given price type.
     * - If price type is not provided, then the default price type will be used.
     * - The SKU can belong to either a concrete or an abstract product.
     * - If it's a concrete product's SKU and it doesn't have any price assigned explicitly, then the price of the
     * abstract product will be returned instead.
     *
     * @api
     *
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceTypeName = null);

    /**
     * Specification:
     *  - Searches for persisted price in database by given price filter transfer.
     *  - If currency not set it will use default store currency.
     *  - If store not set it will use default store.
     *  - If product price type is not set it will use default.
     *  - If price mode is not set it will use default.
     *  - If it's a concrete product and it doesn't have any price assigned explicitly, then the price of the
     * abstract product will be returned instead.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return int|null
     */
    public function findPriceFor(PriceProductFilterTransfer $priceFilterTransfer);

    /**
     * Specification:
     *  - Searches for persisted price in database by given price filter transfer.
     *  - If currency not set it will use default store currency.
     *  - If store not set it will use default store in Dynamic Store OFF mode.
     *  - Requires `PriceProductFilterTransfer::storeName` in Dynamic Store ON mode.
     *  - If product price type is not set it will use default.
     *  - If it's a concrete product and it doesn't have any price assigned explicitly, then the price of the
     * abstract product will be returned instead.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findPriceProductFor(PriceProductFilterTransfer $priceFilterTransfer): ?PriceProductTransfer;

    /**
     * Specification:
     * - Creates a new price type entity and persists it in database.
     * - Returns the ID of the persisted type.
     *
     * @api
     *
     * @param string $name
     *
     * @return int
     */
    public function createPriceType($name);

    /**
     * Specification:
     * - Finds a price type by given name.
     *
     * @api
     *
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer|null
     */
    public function findPriceTypeByName(string $priceTypeName): ?PriceTypeTransfer;

    /**
     * Specification:
     * - Updates existing product price entity with the newly provided data.
     * - If the price type is not defined, then the default price type will be used.
     * - The product to assign can be either concrete or abstract, depending on the provided IDs.
     * - If the product doesn't have price, it throws exception.
     * - Saves new spy_price_product_store record or finds existing one based on gross/net price, store and currency with regenerated PriceDataChecksum.
     * - Deletes orphan spy_price_product_store records based on {@link \Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface} plugin stack execution or configuration.
     * - Touches product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct);

    /**
     * Specification:
     * - Creates the default price type from configuration and persists it in database.
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification:
     * - Searches for a persisted price in database that has the given SKU for the given price type.
     * - If price type is not provided, then the default price type will be used.
     * - The SKU can belong to either a concrete or an abstract product.
     * - If it's a concrete product's SKU and it doesn't have any price assigned explicitly, then the price of the
     * abstract product will be checked instead.
     *
     * @api
     *
     * @deprecated Will be removed in the next major. There is no use of this method in the core, and it wasn't updated for Dynamic Store ON mode usage.
     *
     * @param string $sku
     * @param string|null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null);

    /**
     * Specification:
     *  - Checks if product valid for given price filter configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceProductFilterTransfer $priceFilterTransfer);

    /**
     * Specification:
     * - Creates and assigns a new price product entity for the given product.
     * - If the price type is not defined, then the default price type will be used.
     * - The product to assign can be either concrete or abstract, depending on the provided IDs.
     * - If the product already has price, it throws exception.
     * - Saves new spy_price_product_store record or finds existing one based on gross/net price, store and currency with regenerated PriceDataChecksum.
     * - Deletes orphan spy_price_product_store records based on {@link \Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface} plugin stack execution or configuration.
     * - Touches product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer);

    /**
     * Specification:
     * - Returns the default price type name from configuration.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPriceTypeName();

    /**
     * Specification:
     * - Searches for a persisted price ID in database that has the given SKU for the given price type.
     * - The SKU can belong to either a concrete or an abstract product.
     * - If it's a concrete product's SKU and it doesn't have any price assigned explicitly, then the price ID of the
     * abstract product will be returned instead.
     *
     * @api
     *
     * @deprecated Will be removed without replacement. There is no use of this method, and it's not compatible with Dynamic Store mode.
     *
     * @param string $sku
     * @param string $priceType
     * @param string $currencyIsoCode
     *
     * @return int
     */
    public function getIdPriceProduct($sku, $priceType, $currencyIsoCode);

    /**
     * Specification:
     * - Create new product price entities if they doesn't exists by abstract product id and price type.
     * - Updates the price of product price entities if they exists by abstract product id and price type.
     * - Saves new spy_price_product_store record or finds existing one based on gross/net price, store and currency with regenerated PriceDataChecksum.
     * - Deletes orphan spy_price_product_store records based on {@link \Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface} plugin stack execution or configuration.
     * - Executes PriceDimensionAbstractSaverPluginInterface plugin stack after saving.
     * - If price type wasn't explicitly specified, then the default price type will be used.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function persistProductAbstractPriceCollection(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Create new product price entities if they doesn't exists by concrete product id and price type.
     * - Updates the price of product price entities if they exists by concrete product id and price type.
     * - Saves new spy_price_product_store record or finds existing one based on gross/net price, store and currency with regenerated PriceDataChecksum.
     * - Deletes orphan spy_price_product_store records based on {@link \Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface} plugin stack execution or configuration.
     * - Executes PriceDimensionConcreteSaverPluginInterface plugin stack after saving.
     * - If price type wasn't explicitly specified, then the default price type will be used.
     * - Triggers event 'Product.product_concrete.update'.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePriceCollection(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Reads abstract and concrete product prices from database.
     * - Concrete prices overwrites abstracts for matching price types.
     * - The provided SKU can represent both abstract or concrete product.
     * - Extracts additional prices array from price data
     *
     * @api
     *
     * @param string $sku
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findPricesBySkuForCurrentStore($sku);

    /**
     * Specification:
     *  - Reads prices same as findPricesBySkuForCurrentStore, then groups by currency, price mode, price type for current store.
     *  - Delegates call to findPricesBySkuForCurrentStore and groups result after by currency, price mode and price type.
     *  - Groups provided transfers `priceData` by currency only for BC reasons.
     *  - Groups provided transfers `priceData` by currency and price type.
     *
     * For example:
     *   $result = [
     *      'EUR' => [
     *        'GROSS_MODE' => [
     *           'DEFAULT' => 1000,
     *           'ORIGINAL' => 2000,
     *        ],
     *        'priceData' => '{"volume_prices":[{"quantity":"2","net_price":900,"gross_price":1000}]}',
     *        'priceDataByPriceType' => [
     *            'DEFAULT' => '{"volume_prices":[{"quantity":"2","net_price":900,"gross_price":1000}]}',
     *            'ORIGINAL' => '{"volume_prices":[{"quantity":"2","net_price":700,"gross_price":850}]}'
     *        ],
     *     ]
     *  ];
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return array<mixed>
     */
    public function findPricesBySkuGroupedForCurrentStore(string $sku, ?PriceProductDimensionTransfer $priceProductDimensionTransfer = null): array;

    /**
     * Specification:
     * - Groups provided transfers by currency, price mode and price type.
     * - Groups provided transfers `priceData` by currency only for BC reasons.
     * - Groups provided transfers `priceData` by currency and price type.
     *
     * Example:
     *   $result = [
     *      'EUR' => [
     *        'GROSS_MODE' => [
     *           'DEFAULT' => 1000,
     *           'ORIGINAL' => 2000,
     *        ],
     *        'priceData' => '{"volume_prices":[{"quantity":"2","net_price":900,"gross_price":1000}]}',
     *        'priceDataByPriceType' => [
     *            'DEFAULT' => '{"volume_prices":[{"quantity":"2","net_price":900,"gross_price":1000}]}',
     *            'ORIGINAL' => '{"volume_prices":[{"quantity":"2","net_price":700,"gross_price":850}]}'
     *        ],
     *     ]
     *  ];
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<mixed>
     */
    public function groupPriceProductCollection(array $priceProductTransfers);

    /**
     * Specification:
     * - Reads abstract product prices from database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     * - Extracts additional prices array from price data.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPrices(
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array;

    /**
     * Specification:
     * - Reads abstract and concrete product prices from database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     * - Concrete prices overwrites abstracts for matching price types.
     * - Extracts additional prices array from price data.
     * - Returns only concrete product prices if PriceProductCriteriaTransfer.onlyConcretePrices set to true.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePrices(
        int $idProductConcrete,
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array;

    /**
     * Specification:
     * - Reads the persisted price for the given abstract product id for the given price type.
     * - If price type is not provided, then the default price type will be used.
     * - Returns a hydrated PriceProductTransfer if the price exists, null otherwise.
     * - Extracts additional prices array from price data
     *
     * @api
     *
     * @deprecated Will be removed in the next major. There is no use of this method in the current code base, and it's not compatible with Dynamic Store mode.
     *
     * @param int $idAbstractProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceTypeName = null);

    /**
     * Specification:
     *  - Returns price mode identifier when used identify price which is applicable to gross and net prices type together
     *
     * @api
     *
     * @return string
     */
    public function getPriceModeIdentifierForBothType();

    /**
     * Specification:
     *  - Generates checksum hash for price data field.
     *
     * @api
     *
     * @param array<mixed> $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(array $priceData): string;

    /**
     * Specification:
     *  - Creates new spy_price_product record if it not exists.
     *  - Saves new spy_price_product_store record or finds existing one based on gross/net price, store and currency.
     *  - Deletes orphan spy_price_product_store records based on {@link \Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface} plugin stack execution or configuration.
     *  - Regenerates spy_price_product_store PriceDataChecksum before save.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function persistPriceProductStore(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;

    /**
     * Specification:
     *  - Deletes records from spy_price_product_store without any dimension.
     *
     * @api
     *
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void;

    /**
     * Specification:
     * - Reads abstract product prices from database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesWithoutPriceExtraction(
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array;

    /**
     * Specification:
     * - Reads abstract product prices from database.
     * - Expands each price transfer via array of PriceProductDimensionExpanderStrategyPluginInterface.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn(array $productAbstractIds): array;

    /**
     * Specification:
     * - Builds price criteria object from filter:
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaFromFilter(PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductCriteriaTransfer;

    /**
     * Specification:
     * - Reads abstract and concrete product prices from database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     * - Concrete prices overwrites abstracts for matching price types.
     * - Returns only concrete product prices if PriceProductCriteriaTransfer.onlyConcretePrices set to true.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePricesWithoutPriceExtraction(
        int $idProductConcrete,
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array;

    /**
     * Specification:
     * - Reads information about id product abstract based on product concrete id or sku
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdProductAbstractForPriceProduct(PriceProductTransfer $priceProductTransfer): ?int;

    /**
     * Specification:
     * - Reads abstract product prices from the database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     * - Expands each price transfer via array of PriceProductDimensionExpanderStrategyPluginInterface.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesWithoutPriceExtractionByProductAbstractIdsAndCriteria(
        array $productAbstractIds,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array;

    /**
     * Specification:
     * - Removes price product.
     * - Calls price product store before delete plugins.
     * - Removes price product store.
     * - Adds log message about removing price product.
     *
     * @api
     *
     * @deprecated Please try to avoid removing price product store. Use removePriceProductDefaultForPriceProduct.
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductStore(PriceProductTransfer $priceProductTransfer): void;

    /**
     * Specification:
     * - Reads price product stores filtered by currency, store and price product.
     * - Removes price product default for founded price product stores.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface::deletePriceProductCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductDefaultForPriceProduct(PriceProductTransfer $priceProductTransfer): void;

    /**
     * Specification:
     * - Removes price products by provided criteria transfer.
     * - Executes {@link \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductCollectionDeletePluginInterface} plugin stack.
     * - In the case if PriceProductCollectionDeleteCriteriaTransfer.priceProductDefaultIds is empty, nothing will be deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionResponseTransfer
     */
    public function deletePriceProductCollection(
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionResponseTransfer;

    /**
     * Specification:
     * - Filters product prices using provided filters.
     * - Returns valid price products for given price filter configurations.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getValidPrices(array $priceProductFilterTransfers): array;

    /**
     * Specification:
     * - Checks if price product exists by product identifier and price type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function isPriceProductByProductIdentifierAndPriceTypeExists(
        PriceProductTransfer $priceProductTransfer
    ): bool;

    /**
     * Specification:
     * - Expands ProductConcreteTransfer with concrete product prices for default dimension.
     * - Reads concrete product prices from database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     * - Does not merge abstract concrete prices with concrete product prices.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface::expandProductConcreteTransfersWithPrices()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithPrices(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * Specification:
     * - Expands transfers of product concrete with concrete product prices for default dimension.
     * - Reads concrete product prices from database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     * - Does not merge abstract concrete prices with concrete product prices.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithPrices(array $productConcreteTransfers): array;

    /**
     * Specification:
     * - Validates product prices collection.
     * - Checks if there are duplicated prices for store-currency-gross-net combinations.
     * - Checks that currency is assigned to a store for each price.
     * - Executes PriceProductValidatorPluginInterface plugins.
     * - Returns ValidationResponseTransfer transfer object.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validatePrices(ArrayObject $priceProductTransfers): ValidationResponseTransfer;

    /**
     * Specification:
     * - Expands `WishlistItem` transfer object with a price data.
     * - Return expanded `WishlistItem` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;

    /**
     * Specification:
     * - Merges price data from ProductAbstractTransfer into ProductConcreteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mergeProductAbstractPricesIntoProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer;

    /**
     * Specification:
     * - Reads concrete product prices from database.
     * - Filters results by price type name when provided in criteria.
     * - Filters results by store when provided in criteria.
     * - Filters results by currency when provided in criteria.
     * - Concrete prices overwrites abstracts for matching price types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    public function findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array;
}
