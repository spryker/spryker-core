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
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
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
     * @param string $priceTypeName
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName, $currencyIsoCode, $priceMode)
    {
        return $this->getFactory()->createReaderModel()->getPriceBySku($sku, $priceTypeName, $currencyIsoCode, $priceMode);
    }

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
    public function createPriceType($name)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->createPriceType($name);
    }

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
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $this->getFactory()->createWriterModel()->setPriceForProduct($transferPriceProduct);
    }

    /**
     * Specification:
     * - Creates the default price type from configuration and persists it in database.
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()->createInstaller()->install();
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
     * @param string $priceType
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType, $currencyIsoCode, $priceMode)
    {
        return $this->getFactory()->createReaderModel()->hasValidPrice($sku, $priceType, $currencyIsoCode, $priceMode);
    }

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
     * @param string $currencyIsoCode
     *
     * @return int
     */
    public function getIdPriceProduct($sku, $priceType, $currencyIsoCode)
    {
        return $this->getFactory()->createReaderModel()->getProductPriceIdBySku($sku, $priceType, $currencyIsoCode);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function persistProductAbstractPriceCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->persistProductAbstractPriceCollection($productAbstractTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePriceCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->persistProductConcretePriceCollection($productConcreteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findPricesBySku($sku);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPrices($idProductAbstract)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findProductAbstractPrices($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findProductConcretePrices($idProductConcrete, $idProductAbstract);
    }

}
