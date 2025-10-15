<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterIdentifierTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpanderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class Reader implements ReaderInterface
{
    /**
     * @var string|null
     */
    protected static $priceModeIdentifierForNetType;

    /**
     * @var array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected static $resolvedPriceProductTransferCollection = [];

    /**
     * @var array<string, array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected static array $validPricesCache = [];

    /**
     * @var string
     */
    protected const FIELD_PRICE_PAIR_ABSTRACTS = 'abstracts';

    /**
     * @var string
     */
    protected const FIELD_PRICE_PAIR_CONCRETES = 'concretes';

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceProductTypeReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface $priceProductConcreteReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductAbstractReaderInterface $priceProductAbstractReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface $priceProductMapper
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $config
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductExpanderInterface $priceProductExpander
     */
    public function __construct(
        protected PriceProductToProductFacadeInterface $productFacade,
        protected PriceProductTypeReaderInterface $priceProductTypeReader,
        protected PriceProductConcreteReaderInterface $priceProductConcreteReader,
        protected PriceProductAbstractReaderInterface $priceProductAbstractReader,
        protected PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder,
        protected PriceProductMapperInterface $priceProductMapper,
        protected PriceProductConfig $config,
        protected PriceProductServiceInterface $priceProductService,
        protected PriceProductRepositoryInterface $priceProductRepository,
        protected PriceProductExpanderInterface $priceProductExpander
    ) {
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

        $priceProductTransfer = $this->findProductPrice($sku, $priceProductCriteriaTransfer);

        if ($priceProductTransfer === null) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var string $priceMode */
        $priceMode = $priceProductCriteriaTransfer->requirePriceMode()->getPriceMode();

        return $this->getPriceByPriceMode($moneyValueTransfer, $priceMode);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return int|null
     */
    public function findPriceFor(PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        $priceProductTransfer = $this->findPriceProductFor($priceProductFilterTransfer);

        if ($priceProductTransfer === null) {
            return null;
        }

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var string $priceMode */
        $priceMode = $priceProductCriteriaTransfer->requirePriceMode()->getPriceMode();

        return $this->getPriceByPriceMode($moneyValueTransfer, $priceMode);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findPriceProductFor(PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer
    {
        $priceProductFilterTransfer->requireSku();

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        /** @var string $sku */
        $sku = $priceProductFilterTransfer->requireSku()->getSku();

        return $this->findProductPrice($sku, $priceProductCriteriaTransfer);
    }

    /**
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
    ): array {
        $concretePriceProductTransfers = $this->priceProductConcreteReader
            ->findProductConcretePricesById($idProductConcrete, $priceProductCriteriaTransfer);

        if ($priceProductCriteriaTransfer !== null && $priceProductCriteriaTransfer->getOnlyConcretePrices()) {
            return $concretePriceProductTransfers;
        }

        $abstractPriceProductTransfers = $this->priceProductAbstractReader
            ->findProductAbstractPricesById($idProductAbstract, $priceProductCriteriaTransfer);

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
            $priceProductFilterTransfer->getPriceTypeName(),
        );

        if (!$this->priceProductTypeReader->hasPriceType($priceTypeName)) {
            return false;
        }

        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        /** @var string $sku */
        $sku = $priceProductFilterTransfer->requireSku()->getSku();

        return $this->isValidPrice($sku, $priceProductCriteriaTransfer);
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
            /** @var int $idPriceProduct */
            $idPriceProduct = $this->priceProductConcreteReader->findPriceProductId($sku, $priceProductCriteriaTransfer);

            return $idPriceProduct;
        }

        if (!$this->priceProductAbstractReader->hasPriceForProductAbstract($sku, $priceProductCriteriaTransfer)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        /** @var int $idPriceProduct */
        $idPriceProduct = $this->priceProductAbstractReader->findPriceProductId($sku, $priceProductCriteriaTransfer);

        return $idPriceProduct;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findPricesBySkuForCurrentStore(
        string $sku,
        ?PriceProductDimensionTransfer $priceProductDimensionTransfer = null
    ): array {
        $priceProductDimensionTransfer = $this->getPriceProductDimensionTransfer($priceProductDimensionTransfer);

        $abstractPriceProductTransfers = $this->priceProductAbstractReader
            ->findProductAbstractPricesBySkuForCurrentStore($sku, $priceProductDimensionTransfer);

        $concretePriceProductTransfers = $this->priceProductConcreteReader
            ->findProductConcretePricesBySkuForCurrentStore($sku, $priceProductDimensionTransfer);

        if (!$concretePriceProductTransfers) {
            return $abstractPriceProductTransfers;
        }

        if (!$abstractPriceProductTransfers) {
            return $concretePriceProductTransfers;
        }

        return $this->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $abstractPriceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $concretePriceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function mergeConcreteAndAbstractPrices(
        array $abstractPriceProductTransfers,
        array $concretePriceProductTransfers
    ) {
        $priceProductTransfers = [];
        foreach ($abstractPriceProductTransfers as $priceProductAbstractTransfer) {
            $abstractKey = $this->buildPriceProductIdentifier($priceProductAbstractTransfer);

            $priceProductTransfers = $this->mergeConcreteProduct(
                $concretePriceProductTransfers,
                $abstractKey,
                $priceProductAbstractTransfer,
                $priceProductTransfers,
            );

            if (!isset($priceProductTransfers[$abstractKey])) {
                $priceProductTransfers[$abstractKey] = $priceProductAbstractTransfer;
            }
        }

        return $this->addConcreteNotMergedPrices($concretePriceProductTransfers, $priceProductTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $concretePriceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function addConcreteNotMergedPrices(array $concretePriceProductTransfers, array $priceProductTransfers)
    {
        foreach ($concretePriceProductTransfers as $priceProductConcreteTransfer) {
            $concreteKey = $this->buildPriceProductIdentifier($priceProductConcreteTransfer);

            if (isset($priceProductTransfers[$concreteKey])) {
                continue;
            }

            $priceProductTransfers[$concreteKey] = $priceProductConcreteTransfer;
        }

        return $priceProductTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $concretePriceProductTransfers
     * @param string $abstractKey
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductAbstractTransfer
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function mergeConcreteProduct(
        array $concretePriceProductTransfers,
        $abstractKey,
        PriceProductTransfer $priceProductAbstractTransfer,
        array $priceProductTransfers
    ) {
        foreach ($concretePriceProductTransfers as $priceProductConcreteTransfer) {
            $concreteKey = $this->buildPriceProductIdentifier($priceProductConcreteTransfer);

            if ($abstractKey !== $concreteKey) {
                continue;
            }

            $priceProductTransfers[$concreteKey] = $this->resolveConcreteProductPrice(
                $priceProductAbstractTransfer,
                $priceProductConcreteTransfer,
            );
        }

        if (!isset($priceProductTransfers[$abstractKey])) {
            /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer */
            $priceDimensionTransfer = $priceProductAbstractTransfer->requirePriceDimension()->getPriceDimension();
            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
            $moneyValueTransfer = $priceProductAbstractTransfer->requireMoneyValue()->getMoneyValue();
            $priceDimensionTransfer->setIdPriceProductDefault(null);
            $moneyValueTransfer->setIdEntity(null);
            $priceProductTransfers[$abstractKey] = $priceProductAbstractTransfer->setPriceDimension($priceDimensionTransfer)
                ->setMoneyValue($moneyValueTransfer);
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
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $abstractMoneyValueTransfer */
        $abstractMoneyValueTransfer = $priceProductAbstractTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $concreteMoneyValueTransfer */
        $concreteMoneyValueTransfer = $priceProductConcreteTransfer->requireMoneyValue()->getMoneyValue();

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
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function findProductPrice(string $sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceProductTransfer
    {
        $priceProductConcreteTransfers = $this->priceProductConcreteReader
            ->findProductConcretePricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        if ($this->productFacade->hasProductConcrete($sku)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        $priceProductAbstractTransfers = $this->priceProductAbstractReader
            ->findProductAbstractPricesBySkuAndCriteria($sku, $priceProductCriteriaTransfer);

        if (!$priceProductConcreteTransfers) {
            return $this->priceProductService
                ->resolveProductPriceByPriceProductCriteria($priceProductAbstractTransfers, $priceProductCriteriaTransfer);
        }

        $priceProductTransfers = $this->priceProductService
            ->mergeConcreteAndAbstractPrices($priceProductAbstractTransfers, $priceProductConcreteTransfers);

        return $this->priceProductService
            ->resolveProductPriceByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);
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
                ->setType($this->config->getPriceDimensionDefault());
        }

        return $priceProductDimensionTransfer;
    }

    /**
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
    ): array {
        $concretePriceProductTransfers = $this->priceProductConcreteReader
            ->findProductConcretePricesWithoutPriceExtraction($idProductConcrete, $priceProductCriteriaTransfer);

        if ($priceProductCriteriaTransfer !== null && $priceProductCriteriaTransfer->getOnlyConcretePrices()) {
            return $concretePriceProductTransfers;
        }

        $abstractPriceProductTransfers = $this->priceProductAbstractReader
            ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer);

        return $this->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    protected function buildPriceProductIdentifier(PriceProductTransfer $priceProductTransfer): string
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
        $priceTypeTransfer = $priceProductTransfer->requirePriceType()->getPriceType();

        return implode(
            '-',
            [
                $moneyValueTransfer->getFkStore(),
                $moneyValueTransfer->getFkCurrency(),
                $priceTypeTransfer->getName(),
                $priceTypeTransfer->getPriceModeConfiguration(),
            ],
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getValidPrices(array $priceProductFilterTransfers): array
    {
        if (count($priceProductFilterTransfers) === 0) {
            return [];
        }

        $cacheKey = $this->generateCacheKeyForPriceProductFilters($priceProductFilterTransfers);
        if (isset(static::$validPricesCache[$cacheKey])) {
            return static::$validPricesCache[$cacheKey];
        }

        $priceProductFilterTransfers = $this->filterPriceProductFilterTransfersWithoutExistingPriceType($priceProductFilterTransfers);
        if (!$priceProductFilterTransfers) {
            return [];
        }

        $concretePricesBySku = $this->findPricesForConcreteProducts($priceProductFilterTransfers);
        $abstractPricesBySku = $this->findPricesForAbstractProducts(
            $this->getProductConcreteSkus($priceProductFilterTransfers),
            $priceProductFilterTransfers,
        );

        $priceProductTransfers = $this->resolveProductPrices(
            $this->mergeIndexedPriceProductTransfers($abstractPricesBySku, $concretePricesBySku),
            $priceProductFilterTransfers,
        );

        static::$validPricesCache[$cacheKey] = $priceProductTransfers;

        return $priceProductTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return string
     */
    protected function generateCacheKeyForPriceProductFilters(array $priceProductFilterTransfers): string
    {
        $serializedData = [];
        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $priceProductFilterTransfer = $this->fillPriceProductFilterIdentifier($priceProductFilterTransfer);
            $serializedData[] = $priceProductFilterTransfer->getIdentifier();
        }

        sort($serializedData);

        return md5(implode(',', $serializedData));
    }

    /**
     * @param array<array<\Generated\Shared\Transfer\PriceProductTransfer>> $indexedAbstractPriceProductTransfers
     * @param array<array<\Generated\Shared\Transfer\PriceProductTransfer>> $indexedConcretePriceProductTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected function mergeIndexedPriceProductTransfers(array $indexedAbstractPriceProductTransfers, array $indexedConcretePriceProductTransfers): array
    {
        $mergedPriceProductTransfers = [];

        foreach ($indexedAbstractPriceProductTransfers as $sku => $abstractPriceProductTransfers) {
            $mergedPriceProductTransfers[$sku] = $this->priceProductService->mergeConcreteAndAbstractPrices(
                $abstractPriceProductTransfers,
                $indexedConcretePriceProductTransfers[$sku] ?? [],
            );
        }

        foreach ($indexedConcretePriceProductTransfers as $sku => $concretePriceProductTransfers) {
            $mergedPriceProductTransfers[$sku] = $this->priceProductService->mergeConcreteAndAbstractPrices(
                $indexedAbstractPriceProductTransfers[$sku] ?? [],
                $concretePriceProductTransfers,
            );
        }

        return $mergedPriceProductTransfers;
    }

    /**
     * @param array<array<\Generated\Shared\Transfer\PriceProductTransfer>> $priceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function resolveProductPrices(array $priceProductTransfers, array $priceProductFilterTransfers): array
    {
        $priceProductCriteriaTransfers = $this
            ->priceProductCriteriaBuilder
            ->buildCriteriaTransfersFromFilterTransfers($priceProductFilterTransfers);

        $resolvedPriceProductTransfers = [];
        foreach ($priceProductCriteriaTransfers as $index => $priceProductCriteriaTransfer) {
            $priceProductFilterTransfer = $this->fillPriceProductFilterIdentifier($priceProductFilterTransfers[$index]);
            $resolvedItemPrice = $this->resolveProductPriceByPriceProductCriteria(
                $priceProductFilterTransfer->getIdentifierOrFail(),
                $priceProductTransfers,
                $priceProductCriteriaTransfer,
            );

            if ($resolvedItemPrice) {
                $resolvedPriceProductTransfers[] = $resolvedItemPrice;
            }
        }

        return $resolvedPriceProductTransfers;
    }

    /**
     * @param string $priceProductCriteriaIdentifier
     * @param array<array<\Generated\Shared\Transfer\PriceProductTransfer>> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function resolveProductPriceByPriceProductCriteria(
        string $priceProductCriteriaIdentifier,
        array $priceProductTransfers,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?PriceProductTransfer {
        if (!isset(static::$resolvedPriceProductTransferCollection[$priceProductCriteriaIdentifier])) {
            /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
            $priceProductTransfer = $this->priceProductService->resolveProductPriceByPriceProductCriteria(
                $priceProductTransfers[$priceProductCriteriaIdentifier],
                $priceProductCriteriaTransfer,
            );
            static::$resolvedPriceProductTransferCollection[$priceProductCriteriaIdentifier] = $priceProductTransfer;
        }

        return static::$resolvedPriceProductTransferCollection[$priceProductCriteriaIdentifier];
    }

    /**
     * @return string
     */
    protected function getPriceModeIdentifierForNetType(): string
    {
        if (static::$priceModeIdentifierForNetType === null) {
            static::$priceModeIdentifierForNetType = $this->config->getPriceModeIdentifierForNetType();
        }

        return static::$priceModeIdentifierForNetType;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected function findPricesForConcreteProducts(array $priceProductFilterTransfers): array
    {
        $priceProductFilterTransfer = $this->getCommonPriceProductFilterTransfer($priceProductFilterTransfers);
        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);
        $productConcreteSkus = array_map(function (PriceProductFilterTransfer $priceProductFilterTransfer) {
            /** @var string $sku */
            $sku = $priceProductFilterTransfer->getSku();

            return $sku;
        }, $priceProductFilterTransfers);

        $concretePriceProductTransfers = $this->priceProductConcreteReader->getProductConcretePricesByConcreteSkusAndCriteria(
            $productConcreteSkus,
            $priceProductCriteriaTransfer,
        );

        return $this->groupPriceProductTransfersByFilter($priceProductFilterTransfers, $concretePriceProductTransfers);
    }

    /**
     * @param array<string> $productConcreteSkus
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected function findPricesForAbstractProducts(array $productConcreteSkus, array $priceProductFilterTransfers): array
    {
        $priceProductFilterTransfer = $this->getCommonPriceProductFilterTransfer($priceProductFilterTransfers);
        $priceProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaFromFilter($priceProductFilterTransfer);

        $priceProductTransfers = $this->priceProductAbstractReader->getProductAbstractPricesByConcreteSkusAndCriteria(
            $productConcreteSkus,
            $priceProductCriteriaTransfer,
        );

        return $this->groupPriceProductTransfersByFilter($priceProductFilterTransfers, $priceProductTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected function groupPriceProductTransfersByFilter(array $priceProductFilterTransfers, array $priceProductTransfers): array
    {
        $priceProductTransfersGroupedByFilterIdentifier = [];

        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $priceProductFilterTransfer = $this->fillPriceProductFilterIdentifier($priceProductFilterTransfer);
            $priceProductFilterIdentifier = $priceProductFilterTransfer->getIdentifierOrFail();
            $priceProductTransfersGroupedByFilterIdentifier[$priceProductFilterIdentifier] = $this->priceProductService->resolveProductPricesByPriceProductFilter(
                $priceProductTransfers,
                $priceProductFilterTransfer,
            );
        }

        return $priceProductTransfersGroupedByFilterIdentifier;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<string>
     */
    protected function getProductConcreteSkus(array $priceProductFilterTransfers): array
    {
        $productConcreteSkus = [];

        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            /** @var string $sku */
            $sku = $priceProductFilterTransfer->getSku();

            $productConcreteSkus[] = $sku;
        }

        return $productConcreteSkus;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductFilterTransfer>
     */
    protected function getPriceProductFilterTransfersBySku(array $priceProductFilterTransfers): array
    {
        $priceProductFilterTransfersBySku = [];
        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $priceProductFilterTransfersBySku[$priceProductFilterTransfer->getSku()] = $priceProductFilterTransfer;
        }

        return $priceProductFilterTransfersBySku;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function getCommonPriceProductFilterTransfer(array $priceProductFilterTransfers): PriceProductFilterTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer */
        $priceProductFilterTransfer = array_shift($priceProductFilterTransfers);

        return $priceProductFilterTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductFilterTransfer>
     */
    protected function filterPriceProductFilterTransfersWithoutExistingPriceType(array $priceProductFilterTransfers): array
    {
        $filteredPriceProductFilterTransfers = [];
        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $priceTypeName = $this->priceProductTypeReader->handleDefaultPriceType(
                $priceProductFilterTransfer->getPriceTypeName(),
            );
            if ($this->priceProductTypeReader->hasPriceType($priceTypeName)) {
                $filteredPriceProductFilterTransfers[] = $priceProductFilterTransfer;
            }
        }

        return $filteredPriceProductFilterTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return string
     */
    protected function buildPriceProductFilterIdentifier(PriceProductFilterTransfer $priceProductFilterTransfer): string
    {
        $priceProductFilterIdentifierTransfer = (new PriceProductFilterIdentifierTransfer())->fromArray(
            $priceProductFilterTransfer->toArray(),
            true,
        );
        $priceProductFilterIdentifierTransfer->setQuantity((int)$priceProductFilterTransfer->getQuantity());

        return md5(json_encode($priceProductFilterIdentifierTransfer->toArray(), JSON_THROW_ON_ERROR));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function fillPriceProductFilterIdentifier(PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductFilterTransfer
    {
        if ($priceProductFilterTransfer->getIdentifier() !== null) {
            return $priceProductFilterTransfer;
        }

        return $priceProductFilterTransfer->setIdentifier(
            $this->buildPriceProductFilterIdentifier($priceProductFilterTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    public function findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        $productConcreteToProductAbstractIdMap = $priceProductCriteriaTransfer->getProductConcreteToAbstractIdMaps();
        $productAbstractToProductConcreteIdMap = $this->mapProductAbstractToProductConcreteId($productConcreteToProductAbstractIdMap);
        $priceProductTransfers = $this->getProductPriceTransfers($productConcreteToProductAbstractIdMap, $priceProductCriteriaTransfer);
        $priceProductConcreteProductAbstractPairs = $this->pairPriceProductsTransfersByConcreteToAbstractRelation(
            $priceProductTransfers,
            $productAbstractToProductConcreteIdMap,
        );

        $priceProductTransfersResult = [];
        foreach ($priceProductConcreteProductAbstractPairs as $idProductConcrete => $priceProductPair) {
            $priceProductTransfersResult[(int)$idProductConcrete] = $this->mergeConcreteAndAbstractPrices(
                $priceProductPair[static::FIELD_PRICE_PAIR_ABSTRACTS],
                $priceProductPair[static::FIELD_PRICE_PAIR_CONCRETES],
            );
        }

        return $priceProductTransfersResult;
    }

    /**
     * @param array<int, int> $productConcreteToProductAbstractIdMap
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getProductPriceTransfers(
        array $productConcreteToProductAbstractIdMap,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $productAbstractIds = array_unique(array_values($productConcreteToProductAbstractIdMap));
        $productConcreteIds = array_keys($productConcreteToProductAbstractIdMap);

        $priceProductTransfers = $this->priceProductRepository->findProductPricesByConcreteIdsOrAbstractIds(
            $productConcreteIds,
            $productAbstractIds,
            $priceProductCriteriaTransfer,
        );

        return $this->priceProductExpander->expandPriceProductTransfers($priceProductTransfers);
    }

    /**
     * @param array<int, int> $productConcreteToProductAbstractIdMap
     *
     * @return array<int, array<int>>
     */
    protected function mapProductAbstractToProductConcreteId(array $productConcreteToProductAbstractIdMap): array
    {
        $productAbstractToProductConcreteIdMap = [];
        foreach ($productConcreteToProductAbstractIdMap as $idProductConcrete => $idProductAbstract) {
            $productAbstractToProductConcreteIdMap[$idProductAbstract] = $productAbstractToProductConcreteIdMap[$idProductAbstract] ?? [];
            $productAbstractToProductConcreteIdMap[$idProductAbstract][] = $idProductConcrete;
        }

        return $productAbstractToProductConcreteIdMap;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<int, array<int>> $productAbstractToProductConcreteIdMap
     *
     * @return array<int|string, array<string, array<int, \Generated\Shared\Transfer\PriceProductTransfer>>>
     */
    protected function pairPriceProductsTransfersByConcreteToAbstractRelation(
        array $priceProductTransfers,
        array $productAbstractToProductConcreteIdMap
    ): array {
        $pairStructure = [
            static::FIELD_PRICE_PAIR_ABSTRACTS => [],
            static::FIELD_PRICE_PAIR_CONCRETES => [],
        ];
        $priceProductConcreteProductAbstractPairs = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
            $idProductConcrete = $priceProductTransfer->getIdProduct();

            if (!$idProductAbstract || empty($productAbstractToProductConcreteIdMap[$idProductAbstract])) {
                $priceProductConcreteProductAbstractPairs[$idProductConcrete] ??= $pairStructure;
                $priceProductConcreteProductAbstractPairs[$idProductConcrete][static::FIELD_PRICE_PAIR_CONCRETES][] = $priceProductTransfer;

                continue;
            }

            foreach ($productAbstractToProductConcreteIdMap[$idProductAbstract] as $idProductConcreteRelated) {
                $priceProductConcreteProductAbstractPairs[$idProductConcreteRelated] ??= $pairStructure;
                $priceProductConcreteProductAbstractPairs[$idProductConcreteRelated][static::FIELD_PRICE_PAIR_ABSTRACTS][] = $priceProductTransfer;
            }
        }

        return $priceProductConcreteProductAbstractPairs;
    }
}
