<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\Price\Business\PriceBusinessFactory getFactory()
 */
class PriceFacade extends AbstractFacade implements PriceFacadeInterface
{

    /**
     * Specification:
     * - Reads all persisted price types and returns their names in an array.
     *
     * @api
     *
     * @return array
     */
    public function getPriceTypeValues()
    {
        return $this->getFactory()->createReaderModel()->getPriceTypes();
    }

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
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        return $this->getFactory()->createReaderModel()->getPriceBySku($sku, $priceTypeName);
    }

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
    public function getProductAbstractPrice($idAbstractProduct, $priceTypeName = null)
    {
        return $this->getFactory()->createReaderModel()->getProductAbstractPrice($idAbstractProduct, $priceTypeName);
    }

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
    public function getProductConcretePrice($idProduct, $priceTypeName = null)
    {
        return $this->getFactory()->createReaderModel()->getProductConcretePrice($idProduct, $priceTypeName);
    }

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
    public function createPriceType($name)
    {
        return $this->getFactory()->createWriterModel()->createPriceType($name);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return mixed
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        return $this->getFactory()->createWriterModel()->setPriceForProduct($transferPriceProduct);
    }

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
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

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
    public function hasValidPrice($sku, $priceType = null)
    {
        return $this->getFactory()->createReaderModel()->hasValidPrice($sku, $priceType);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $this->getFactory()->createWriterModel()->createPriceForProduct($transferPriceProduct);
    }

    /**
     * Specification:
     * - Returns the default price type name from configuration.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->getFactory()->getConfig()->getPriceTypeDefaultName();
    }

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
    public function getIdPriceProduct($sku, $priceType)
    {
        return $this->getFactory()->createReaderModel()->getProductPriceIdBySku($sku, $priceType);
    }

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
    public function persistProductAbstractPrice(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()->createWriterModel()->persistProductAbstractPrice($productAbstractTransfer);
    }

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
    public function persistProductConcretePrice(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()->createWriterModel()->persistProductConcretePrice($productConcreteTransfer);
    }

}
