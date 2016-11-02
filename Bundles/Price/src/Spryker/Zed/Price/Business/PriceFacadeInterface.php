<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

interface PriceFacadeInterface
{

    /**
     * Specification:
     * - Reads all persisted price types and returns their names in an array.
     *
     * @api
     *
     * @return array
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
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null);

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

    /**
     * Specification:
     * - Reads the persisted price for the given concrete product id for the given price type.
     * - If price type is not provided, then the default price type will be used.
     * - If the price is not found, then it'll read the abstract product price instead.
     * - Returns a hydrated PriceProductTransfer if one of the concrete or abstract price exists, null otherwise.
     *
     * @api
     *
     * @param int $idProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductConcretePrice($idProduct, $priceTypeName = null);

    /**
     * Specification:
     * - Creates a new price type entity and persists it in database.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceType
     */
    public function createPriceType($name);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return mixed
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct);

    /**
     * Specification:
     * - Creates the default price type from configuration and persists it in database.
     *
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger);

    /**
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
     *
     * @return int
     */
    public function getIdPriceProduct($sku, $priceType);

    /**
     * Specification:
     * - Create a new product price entity if it doesn't exists by abstract product id and price type.
     * - Updates the price of a product price entity if it exists by abstract product id and price type.
     * - If price type wasn't explicitly specified, then the default price type will be used.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function persistProductAbstractPrice(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Create a new product price entity if it doesn't exists by concrete product id and price type.
     * - Updates the price of a product price entity if it exists by concrete product id and price type.
     * - If price type wasn't explicitly specified, then the default price type will be used.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePrice(ProductConcreteTransfer $productConcreteTransfer);

}
