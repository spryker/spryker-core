<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use Generated\Shared\Transfer\PriceFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface PriceProductFacadeInterface
{

    /**
     * Specification:
     * - Reads all persisted price types and returns their names in an array.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
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
     * @param string $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null);

    /**
     * Specification:
     *  - Searches for persisted price in database by given price filter transfer.
     *  - If currency not set it will use default store currency.
     *  - If store not set it will use default store.
     *  - If product price type is not set it will use default.
     *  - If price mode is not set it will use default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return int
     */
    public function getPriceFor(PriceFilterTransfer $priceFilterTransfer);

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
     * - Updates existing product price entity with the newly provided data.
     * - If the price type is not defined, then the default price type will be used.
     * - The product to assign can be either concrete or abstract, depending on the provided IDs.
     * - If the product doesn't have price, it throws exception.
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
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceFilterTransfer $priceFilterTransfer);

    /**
     * Specification:
     * - Creates and assigns a new price product entity for the given product.
     * - If the price type is not defined, then the default price type will be used.
     * - The product to assign can be either concrete or abstract, depending on the provided IDs.
     * - If the product already has price, it throws exception.
     * - Touches product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct);

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
     * - If price type wasn't explicitly specified, then the default price type will be used.
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
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku);

    /**
     * Specification:
     *  - Reads prices same as findPricesBySku, then groups by currency, price mode, price type
     *
     * @api
     *
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGrouped($sku);

    /**
     * Specification:
     * - Reads abstract product prices from database.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPrices($idProductAbstract);

    /**
     * Specification:
     * - Reads abstract and concrete product prices from database.
     * - Concrete prices overwrites abstracts for matching price types.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract);

    /**
     * Specification:
     * - Reads the persisted price for the given abstract product id for the given price type.
     * - If price type is not provided, then the default price type will be used.
     * - Returns a hydrated PriceProductTransfer if the price exists, null otherwise.
     *
     * @api
     *
     * @param int $idAbstractProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceTypeName = null);

}
