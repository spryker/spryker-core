<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
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
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface
     */
    protected $priceProductMapper;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceProductTypeReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface $priceProductConcreteReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface $priceProductAbstractReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface $priceProductMapper
     */
    public function __construct(
        PriceProductToProductFacadeInterface $productFacade,
        PriceProductTypeReaderInterface $priceProductTypeReader,
        PriceProductConcreteReaderInterface $priceProductConcreteReader,
        PriceProductAbstractReaderInterface $priceProductAbstractReader,
        PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder,
        PriceProductMapperInterface $priceProductMapper
    ) {
        $this->productFacade = $productFacade;
        $this->priceProductTypeReader = $priceProductTypeReader;
        $this->priceProductConcreteReader = $priceProductConcreteReader;
        $this->priceProductAbstractReader = $priceProductAbstractReader;
        $this->priceProductCriteriaBuilder = $priceProductCriteriaBuilder;
        $this->priceProductMapper = $priceProductMapper;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceTypeName = null)
    {
        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaWithDefaultValues($priceTypeName);

        return $this->findProductPrice($sku, $priceProductCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return int|null
     */
    public function findPriceFor(PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        $priceProductFilterTransfer->requireSku();

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        return $this->findProductPrice($priceProductFilterTransfer->getSku(), $priceProductCriteriaTransfer);
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices(
        $idProductConcrete,
        $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ) {
        $abstractPriceProductTransfers = $this->priceProductAbstractReader
            ->findProductAbstractPricesById($idProductAbstract, $priceProductCriteriaTransfer);

        $concretePriceProductTransfers = $this->priceProductConcreteReader
            ->findProductConcretePricesById($idProductConcrete, $priceProductCriteriaTransfer);

        return $this->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);
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

        return $this->isValidPrice($sku, $priceProductCriteriaTransfer);
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

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        return $this->isValidPrice($priceProductFilterTransfer->getSku(), $priceProductCriteriaTransfer);
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
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySkuForCurrentStore(
        $sku,
        ?PriceProductDimensionTransfer $priceProductDimensionTransfer = null
    ) {
        $priceProductDimensionTransfer = $this->getPriceProductDimensionTransfer($priceProductDimensionTransfer);

        $abstractPriceProductTransfers = $this->priceProductAbstractReader
            ->findProductAbstractPricesBySkuForCurrentStore($sku, $priceProductDimensionTransfer);

        $concretePriceProductTransfers = $this->priceProductConcreteReader
            ->findProductConcretePricesBySkuForCurrentStore($sku, $priceProductDimensionTransfer);

        if (empty($concretePriceProductTransfers)) {
            return $abstractPriceProductTransfers;
        }

        if (empty($abstractPriceProductTransfers)) {
            return $concretePriceProductTransfers;
        }

        return $this->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function mergeConcreteAndAbstractPrices(
        array $abstractPriceProductTransfers,
        array $concretePriceProductTransfers
    ) {
        $priceProductTransfers = [];
        foreach ($abstractPriceProductTransfers as $abstractKey => $priceProductAbstractTransfer) {
            $priceProductTransfers = $this->mergeConcreteProduct(
                $concretePriceProductTransfers,
                $abstractKey,
                $priceProductAbstractTransfer,
                $priceProductTransfers
            );

            if (!isset($priceProductTransfers[$abstractKey])) {
                $priceProductTransfers[$abstractKey] = $priceProductAbstractTransfer;
            }
        }

        return $this->addConcreteNotMergedPrices($concretePriceProductTransfers, $priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function addConcreteNotMergedPrices(array $concretePriceProductTransfers, array $priceProductTransfers)
    {
        foreach ($concretePriceProductTransfers as $concreteKey => $priceProductConcreteTransfer) {
            if (isset($priceProductTransfers[$concreteKey])) {
                continue;
            }

            $priceProductTransfers[$concreteKey] = $priceProductConcreteTransfer;
        }

        return $priceProductTransfers;
    }

    /**
     * @param array $concretePriceProductTransfers
     * @param string $abstractKey
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductAbstractTransfer
     * @param array $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function mergeConcreteProduct(
        array $concretePriceProductTransfers,
        $abstractKey,
        PriceProductTransfer $priceProductAbstractTransfer,
        array $priceProductTransfers
    ) {
        foreach ($concretePriceProductTransfers as $concreteKey => $priceProductConcreteTransfer) {
            if ($abstractKey !== $concreteKey) {
                continue;
            }

            $priceProductTransfers[$concreteKey] = $this->resolveConcreteProductPrice(
                $priceProductAbstractTransfer,
                $priceProductConcreteTransfer
            );
        }

        if (!isset($priceProductTransfers[$abstractKey])) {
            $priceProductAbstractTransfer->getPriceDimension()->setIdPriceProductDefault(null);
            $priceProductAbstractTransfer->getMoneyValue()->setIdEntity(null);
            $priceProductTransfers[$abstractKey] = $priceProductAbstractTransfer;
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductAbstractTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function resolveConcreteProductPrice(
        PriceProductTransfer $priceProductAbstractTransfer,
        PriceProductTransfer $priceProductConcreteTransfer
    ) {

        $abstractMoneyValueTransfer = $priceProductAbstractTransfer->getMoneyValue();
        $concreteMoneyValueTransfer = $priceProductConcreteTransfer->getMoneyValue();

        if ($concreteMoneyValueTransfer->getGrossAmount() === null) {
            $concreteMoneyValueTransfer->setGrossAmount($abstractMoneyValueTransfer->getGrossAmount());
        }

        if ($concreteMoneyValueTransfer->getNetAmount() === null) {
            $concreteMoneyValueTransfer->setNetAmount($abstractMoneyValueTransfer->getNetAmount());
        }

        return $priceProductConcreteTransfer;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    protected function findProductPrice($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        $priceProductConcrete = $this->priceProductConcreteReader
            ->findPriceForProductConcrete($sku, $priceProductCriteriaTransfer);

        if ($priceProductConcrete !== null) {
            return $this->getPriceByPriceMode($priceProductConcrete->getMoneyValue(), $priceProductCriteriaTransfer->getPriceMode());
        }

        if ($this->productFacade->hasProductConcrete($sku)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        $priceProductAbstract = $this->priceProductAbstractReader
            ->findPriceForProductAbstract($sku, $priceProductCriteriaTransfer);

        if (!$priceProductAbstract) {
            return null;
        }

        return $this->getPriceByPriceMode($priceProductAbstract->getMoneyValue(), $priceProductCriteriaTransfer->getPriceMode());
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param string $priceMode
     *
     * @return int|null
     */
    protected function getPriceByPriceMode(MoneyValueTransfer $moneyValueTransfer, string $priceMode): ?int
    {
        if ($priceMode === $this->priceProductMapper->getNetPriceModeIdentifier()) {
            return $moneyValueTransfer->getNetAmount();
        }

        return $moneyValueTransfer->getGrossAmount();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return bool
     */
    protected function isValidPrice($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        if ($this->priceProductConcreteReader->hasPriceForProductConcrete($sku, $priceProductCriteriaTransfer)) {
            return true;
        }

        $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        if ($this->priceProductAbstractReader->hasPriceForProductAbstract($abstractSku, $priceProductCriteriaTransfer)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function getPriceProductDimensionTransfer(?PriceProductDimensionTransfer $priceProductDimensionTransfer = null): PriceProductDimensionTransfer
    {
        if (!$priceProductDimensionTransfer) {
            $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
                ->setType(PriceProductConstants::PRICE_DIMENSION_DEFAULT);
        }

        return $priceProductDimensionTransfer;
    }
}
