<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory getFactory()
 */
class PriceProductFacade extends AbstractFacade implements PriceProductFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypeValues()
    {
        return $this->getFactory()
            ->createPriceTypeReader()
            ->getPriceTypes();
    }

    /**
     * {@inheritdoc}
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
        return $this->getFactory()
            ->createReaderModel()
            ->getPriceBySku($sku, $priceTypeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return int
     */
    public function getPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getPriceFor($priceFilterTransfer);
    }

    /**
     * {@inheritdoc}
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
            ->createPriceTypeWriter()
            ->createPriceType($name);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $this->getFactory()
            ->createWriterModel()
            ->setPriceForProduct($transferPriceProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()
            ->createInstaller()
            ->install();
    }

    /**
     * {@inheritdoc}
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
        return $this->getFactory()
            ->createReaderModel()
            ->hasValidPrice($sku, $priceType);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->hasValidPriceFor($priceFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->createPriceForProduct($priceProductTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->getFactory()
            ->getConfig()
            ->getPriceTypeDefaultName();
    }

    /**
     * {@inheritdoc}
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
        return $this->getFactory()
            ->createReaderModel()
            ->getProductPriceIdBySku($sku, $priceType, $currencyIsoCode);
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
            ->createPriceProductAbstractWriter()
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
            ->createPriceProductConcreteWriter()
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
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGrouped($sku)
    {
        return $this->getFactory()
            ->createPriceGrouper()
            ->findPricesBySkuGrouped($sku);
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
            ->createPriceProductAbstractReader()
            ->findProductAbstractPricesById($idProductAbstract);
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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idAbstractProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceTypeName = null)
    {
        return $this->getFactory()
            ->createPriceProductAbstractReader()
            ->findProductAbstractPrice($idAbstractProduct, $priceTypeName);
    }
}
