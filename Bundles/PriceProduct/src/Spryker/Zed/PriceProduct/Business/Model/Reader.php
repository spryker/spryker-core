<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Zed\PriceProduct\Business\Exception\MissingPriceException;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;

class Reader implements ReaderInterface
{
    const PRICE_TYPE_UNKNOWN = 'price type unknown: ';

    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifier;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected $priceProductTypeReader;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface
     */
    protected $priceProductConcreteReader;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface
     */
    protected $priceProductAbstractReader;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface
     */
    protected $priceProductCriteriaBuilder;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceProductTypeReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface $priceProductConcreteReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface $priceProductAbstractReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder
     */
    public function __construct(
        PriceProductToProductInterface $productFacade,
        PriceProductToPriceInterface $priceFacade,
        PriceProductTypeReaderInterface $priceProductTypeReader,
        PriceProductConcreteReaderInterface $priceProductConcreteReader,
        PriceProductAbstractReaderInterface $priceProductAbstractReader,
        PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder
    ) {
        $this->productFacade = $productFacade;
        $this->priceFacade = $priceFacade;
        $this->priceProductTypeReader = $priceProductTypeReader;
        $this->priceProductConcreteReader = $priceProductConcreteReader;
        $this->priceProductAbstractReader = $priceProductAbstractReader;
        $this->priceProductCriteriaBuilder = $priceProductCriteriaBuilder;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaWithDefaultValues($priceTypeName);
        $productPrice = $this->getProductPrice($sku, $priceProductCriteriaTransfer);

        return $this->findPriceByPriceMode($priceProductCriteriaTransfer, $productPrice);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return mixed
     */
    public function getPriceFor(PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        $priceProductFilterTransfer->requireSku();

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);
        $productPrice = $this->getProductPrice($priceProductFilterTransfer->getSku(), $priceProductCriteriaTransfer);

        return $this->findPriceByPriceMode($priceProductCriteriaTransfer, $productPrice);
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract)
    {
        $abstractPriceProductTransfers = $this->priceProductAbstractReader->findProductAbstractPricesById($idProductAbstract);
        $concretePriceProductTransfers = $this->priceProductConcreteReader->findProductConcretePricesById($idProductConcrete);

        $priceProductTransfers = array_merge($abstractPriceProductTransfers, $concretePriceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceTypeName = null)
    {
        $priceTypeName = $this->priceProductTypeReader->handleDefaultPriceType($priceTypeName);

        if (!$this->priceProductTypeReader->hasPriceType($priceTypeName)) {
            return false;
        }

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaWithDefaultValues();

        return $this->isValidProduct($sku, $priceProductCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        $priceProductFilterTransfer->requireSku();

        $priceTypeName = $this->priceProductTypeReader->handleDefaultPriceType(
            $priceProductFilterTransfer->getPriceTypeName()
        );

        if (!$this->priceProductTypeReader->hasPriceType($priceTypeName)) {
            return false;
        }

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaWithDefaultValues();

        return $this->isValidProduct($priceProductFilterTransfer->getSku(), $priceProductCriteriaTransfer);
    }

    /**
     * @param string $sku
     * @param string $priceTypeName
     * @param string $currencyIsoCode
     *
     * @return int
     */
    public function getProductPriceIdBySku($sku, $priceTypeName, $currencyIsoCode)
    {
        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaWithDefaultValues();

        if ($this->priceProductConcreteReader->hasPriceForProductConcrete($sku, $priceProductCriteriaTransfer)) {
            return $this->priceProductConcreteReader->findPriceProductId($sku, $priceProductCriteriaTransfer);
        }

        if (!$this->priceProductAbstractReader->hasPriceForProductAbstract($sku, $priceProductCriteriaTransfer)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        return $this->priceProductAbstractReader->findPriceProductId($sku, $priceProductCriteriaTransfer);
    }

    /**
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGrouped($sku)
    {
        $priceProductTransfers = $this->findPricesBySku($sku);

        $prices = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceMoneyValueTransfer = $priceProductTransfer->getMoneyValue();

            $priceType = $priceProductTransfer->getPriceType()->getName();
            $currency = $priceMoneyValueTransfer->getCurrency()->getCode();

            if ($priceMoneyValueTransfer->getGrossAmount()) {
                $prices[$currency][$this->getGrossPriceModeIdentifier()][$priceType] = $priceMoneyValueTransfer->getGrossAmount();
            }

            if ($priceMoneyValueTransfer->getNetAmount()) {
                $prices[$currency][$this->getNetPriceModeIdentifier()][$priceType] = $priceMoneyValueTransfer->getNetAmount();
            }
        }

        return $prices;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku)
    {
        $abstractPriceProductTransfers = $this->priceProductAbstractReader->findProductAbstractPricesBySku($sku);
        $concretePriceProductTransfers = $this->priceProductConcreteReader->findProductConcretePricesBySku($sku);

        $priceProductTransfers = array_merge($abstractPriceProductTransfers, $concretePriceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @throws \Spryker\Zed\PriceProduct\Business\Exception\MissingPriceException
     *
     * @return array
     */
    protected function getProductPrice($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        $priceProductConcrete = $this->priceProductConcreteReader->getPriceForProductConcrete($sku, $priceProductCriteriaTransfer);
        if ($priceProductConcrete !== null) {
            return $priceProductConcrete;
        }

        $priceProductAbstract = $this->priceProductAbstractReader->getPriceForProductAbstract($sku, $priceProductCriteriaTransfer);
        if ($priceProductAbstract !== null) {
            return $priceProductAbstract;
        }

        if ($this->productFacade->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            $priceProductAbstract = $this->priceProductAbstractReader->getPriceForProductAbstract($abstractSku, $priceProductCriteriaTransfer);

            if ($priceProductAbstract !== null) {
                return $priceProductAbstract;
            }
        }

        throw new MissingPriceException(sprintf(
            'Price not found for product with SKU: "%s".',
            $sku
        ));
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return bool
     */
    protected function isValidProduct($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        if ($this->priceProductConcreteReader->hasPriceForProductConcrete($sku, $priceProductCriteriaTransfer) ||
            $this->priceProductAbstractReader->hasPriceForProductAbstract($sku, $priceProductCriteriaTransfer)) {
            return true;
        }

        if ($this->productFacade->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            if ($this->productFacade->hasProductAbstract($abstractSku) &&
                $this->priceProductAbstractReader->hasPriceForProductAbstract($abstractSku, $priceProductCriteriaTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        if (!static::$grossPriceModeIdentifier) {
            static::$grossPriceModeIdentifier = $this->priceFacade->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifier;
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     * @param array $productPrice
     *
     * @return int
     */
    protected function findPriceByPriceMode(PriceProductCriteriaTransfer $priceProductCriteriaTransfer, array $productPrice)
    {
        if ($priceProductCriteriaTransfer->getPriceMode() === $this->getNetPriceModeIdentifier()) {
            return (int)$productPrice[PriceProductQueryContainerInterface::COL_NET_PRICE];
        }

        return (int)$productPrice[PriceProductQueryContainerInterface::COL_GROSS_PRICE];
    }
}
