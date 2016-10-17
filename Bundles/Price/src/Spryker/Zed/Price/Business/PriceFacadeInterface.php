<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

interface PriceFacadeInterface
{

    /**
     * @api
     *
     * @return array
     */
    public function getPriceTypeValues();

    /**
     * @api
     *
     * @param string $sku
     * @param string|null $priceType
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceType = null);

    /**
     * @api
     *
     * @param int $idAbstractProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function getProductAbstractPrice($idAbstractProduct, $priceType = null);

    /**
     * @api
     *
     * @param int $idProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function getProductConcretePrice($idProduct, $priceType = null);

    /**
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
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger);

    /**
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
     * @api
     *
     * @return string
     */
    public function getDefaultPriceTypeName();

    /**
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceTransfer
     *
     * @return int
     */
    public function persistProductAbstractPrice(PriceProductTransfer $priceTransfer);

    /**
     * Specification:
     * - Create a new product price entity if it doesn't exists by concrete product id and price type.
     * - Updates the price of a product price entity if it exists by concrete product id and price type.
     * - If price type wasn't explicitly specified, then the default price type will be used.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceTransfer
     *
     * @return int
     */
    public function persistProductConcretePrice(PriceProductTransfer $priceTransfer);

}
