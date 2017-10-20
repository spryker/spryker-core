<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\PriceProduct\Business\Exception\MissingPriceException;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class Reader implements ReaderInterface
{

    const PRICE_TYPE_UNKNOWN = 'price type unknown: ';
    const SKU_UNKNOWN = 'sku unknown';
    const COL_GROSS_PRICE = 'gross_price';
    const COL_NET_PRICE = 'net_price';

    protected static $netPriceModeIdentifier;
    protected static $grossPriceModeIdentifier;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceConfig;

    /**
     * @var array
     */
    protected $priceTypeEntityByNameCache = [];

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceConfig
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface $storeFacade
     */
    public function __construct(
        PriceProductQueryContainerInterface $queryContainer,
        PriceProductToProductInterface $productFacade,
        PriceProductConfig $priceConfig,
        PriceProductToCurrencyInterface $currencyFacade,
        PriceProductToPriceInterface $priceFacade,
        PriceProductToStoreInterface $storeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->priceConfig = $priceConfig;
        $this->currencyFacade = $currencyFacade;
        $this->priceFacade = $priceFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypes()
    {
        $priceTypes = [];
        $priceTypeEntities = $this->queryContainer
            ->queryAllPriceTypes()
            ->find();

        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceTypeEntity */
        foreach ($priceTypeEntities as $priceTypeEntity) {
            $priceModeTransfer = new PriceTypeTransfer();
            $priceModeTransfer->fromArray($priceTypeEntity->toArray(), true);
            $priceTypes[] = $priceModeTransfer;
        }

        return $priceTypes;
    }

    /**
     * @param string $sku
     * @param string $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);

        $defaultPriceMode = $this->priceFacade->getDefaultPriceMode();
        $currencyTransfer = $this->currencyFacade->getDefaultCurrencyForCurrentStore();
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $productPrice = $this->getProductPrice(
            $sku,
            $priceTypeName,
            $currencyTransfer->getIdCurrency(),
            $storeTransfer->getIdStore()
        );

        if ($defaultPriceMode === $this->getNetPriceModeIdentifier()) {
            return $productPrice[static::COL_NET_PRICE];
        }

        return $productPrice[static::COL_GROSS_PRICE];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return int
     */
    public function getPriceFor(PriceFilterTransfer $priceFilterTransfer)
    {
        $priceFilterTransfer->requireSku();

        $priceMode = $this->getPriceModeFromFilter($priceFilterTransfer);
        $currencyTransfer = $this->getCurrencyFromFilter($priceFilterTransfer);
        $storeTransfer = $this->getStoreFromFilter($priceFilterTransfer);
        $priceTypeName = $this->handleDefaultPriceType($priceFilterTransfer->getPriceTypeName());

        $productPrice = $this->getProductPrice(
            $priceFilterTransfer->getSku(),
            $priceTypeName,
            $currencyTransfer->getIdCurrency(),
            $storeTransfer->getIdStore()
        );

        if ($priceMode === $this->getNetPriceModeIdentifier()) {
            return $productPrice[static::COL_NET_PRICE];
        }

        return $productPrice[static::COL_GROSS_PRICE];
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku)
    {
        $abstractPriceProductTransfers = $this->findProductAbstractPricesBySku($sku);
        $concretePriceProductTransfers = $this->findProductConcretePricesBySku($sku);

        $priceProductTransfers = array_merge($abstractPriceProductTransfers, $concretePriceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPrices($idProductAbstract)
    {
        return $this->findProductAbstractPricesById($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract)
    {
        $abstractPriceProductTransfers = $this->findProductAbstractPricesById($idProductAbstract);
        $concretePriceProductTransfers = $this->findProductConcretePricesById($idProductConcrete);

        $priceProductTransfers = array_merge($abstractPriceProductTransfers, $concretePriceProductTransfers);

        return $priceProductTransfers;
    }

    /**
    /**
     * @param string $priceTypeName
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceType
     */
    public function getPriceTypeByName($priceTypeName)
    {
        if (!$this->hasPriceType($priceTypeName)) {
            throw new Exception(self::PRICE_TYPE_UNKNOWN . $priceTypeName);
        }

        return $this->priceTypeEntityByNameCache[$priceTypeName];
    }

    /**
     * @param string $priceTypeName
     *
     * @return bool
     */
    protected function hasPriceType($priceTypeName)
    {
        if (!isset($this->priceTypeEntityByNameCache[$priceTypeName])) {
            $priceTypeEntity = $this->queryContainer->queryPriceType($priceTypeName)->findOne();

            if ($priceTypeEntity === null) {
                return false;
            }

            $this->priceTypeEntityByNameCache[$priceTypeName] = $priceTypeEntity;
        }

        return true;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);

        if (!$this->hasPriceType($priceTypeName)) {
            return false;
        }

        $currencyTransfer = $this->currencyFacade->getDefaultCurrencyForCurrentStore();
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();
        $idCurrency = $currencyTransfer->getIdCurrency();

        return $this->isValidProduct($sku, $priceTypeName, $idCurrency, $idStore);
    }

    /**
     * @param string $sku
     * @param string $priceTypeName
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return bool
     */
    protected function isValidProduct($sku, $priceTypeName, $idCurrency, $idStore)
    {
        if ($this->hasPriceForProductConcrete($sku, $priceTypeName, $idCurrency, $idStore) ||
            $this->hasPriceForProductAbstract($sku, $priceTypeName, $idCurrency, $idStore)) {
            return true;
        }

        if ($this->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            if ($this->hasProductAbstract($abstractSku) &&
                $this->hasPriceForProductAbstract($abstractSku, $priceTypeName, $idCurrency, $idStore)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceFilterTransfer $priceFilterTransfer)
    {
        $priceFilterTransfer->requireSku();

        if (!$this->hasPriceType($priceFilterTransfer->getPriceTypeName())) {
            return false;
        }

        $currencyTransfer = $this->getCurrencyFromFilter($priceFilterTransfer);
        $storeTransfer = $this->getStoreFromFilter($priceFilterTransfer);

        return $this->isValidProduct(
            $priceFilterTransfer->getSku(),
            $priceFilterTransfer->getPriceTypeName(),
            $currencyTransfer->getIdCurrency(),
            $storeTransfer->getIdStore()
        );
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku)
    {
        return $this->productFacade->hasProductConcrete($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        return $this->productFacade->hasProductAbstract($sku);
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
        $priceType = $this->getPriceTypeByName($priceTypeName);

        $currencyTransfer = $this->currencyFacade->fromIsoCode($currencyIsoCode);
        $idCurrency = $currencyTransfer->getIdCurrency();
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();

        if ($this->hasPriceForProductConcrete($sku, $priceType, $idCurrency, $idStore)) {
            return $this->queryContainer
                ->queryPriceEntityForProductConcrete($sku, $priceType, $idCurrency, $idStore)
                ->findOne()
                ->getIdPriceProduct();
        }

        if (!$this->hasPriceForProductAbstract($sku, $priceType, $idCurrency, $idStore)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        return $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType, $idCurrency, $idStore)
            ->findOne()
            ->getIdPriceProduct();
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return array
     * @throws \Spryker\Zed\PriceProduct\Business\Exception\MissingPriceException
     */
    protected function getProductPrice($sku, $priceType, $idCurrency, $idStore)
    {
        $priceProductConcrete = $this->getPriceForProductConcrete($sku, $priceType, $idCurrency, $idStore);
        if ($priceProductConcrete !== null) {
            return $priceProductConcrete;
        }

        $priceProductAbstract = $this->getPriceForProductAbstract($sku, $priceType, $idCurrency, $idStore);
        if ($priceProductAbstract !== null) {
            return $priceProductAbstract;
        }

        if ($this->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            $priceProductAbstract = $this->getPriceForProductAbstract($abstractSku, $priceType, $idCurrency, $idStore);

            if ($priceProductAbstract !== null) {
                return $priceProductAbstract;
            }
        }

        throw new MissingPriceException(sprintf(
            'Price not found for product with SKU: %s!',
            $sku
        ));
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return bool
     */
    protected function hasPriceForProductConcrete($sku, $priceType, $idCurrency, $idStore)
    {
        $productConcrete = $this->queryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceType, $idCurrency, $idStore)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        return $productConcrete !== null;
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return bool
     */
    protected function hasPriceForProductAbstract($sku, $priceType, $idCurrency, $idStore)
    {
        $productAbstract = $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType, $idCurrency, $idStore)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        return $productAbstract !== null;
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return array
     */
    protected function getPriceForProductConcrete($sku, $priceType, $idCurrency, $idStore)
    {
        return $this->queryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceType, $idCurrency, $idStore)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, static::COL_GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, static::COL_NET_PRICE)
            ->setFormatter(ArrayFormatter::class)
            ->findOne();
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return array
     */
    protected function getPriceForProductAbstract($sku, $priceType, $idCurrency, $idStore)
    {
        return $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType, $idCurrency, $idStore)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, static::COL_GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, static::COL_NET_PRICE)
            ->setFormatter(ArrayFormatter::class)
            ->findOne();
    }

    /**
     * @param string|null $priceType
     *
     * @return string
     */
    public function handleDefaultPriceType($priceType = null)
    {
        if ($priceType === null) {
            $priceType = $this->priceConfig->getPriceTypeDefaultName();
        }

        return $priceType;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku)
    {
        return $this->productFacade->findProductAbstractIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku)
    {
        return $this->productFacade->getProductConcreteIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findProductAbstractPricesBySku($sku)
    {
        $abstractSku = $this->getAbstractSku($sku);
        $productAbstractPriceEntities = $this->queryContainer
            ->queryPricesForProductAbstractBySku($abstractSku)
            ->find();

        return $this->mapPriceProductTransferCollectionForProductAbstract($productAbstractPriceEntities);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct[] $priceProductEntities
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function mapPriceProductTransferCollectionForProductAbstract($priceProductEntities)
    {
        $productPriceCollection = [];
        foreach ($priceProductEntities as $priceProductEntity) {
            foreach ($priceProductEntity->getPriceProductStores() as $priceProductStoreEntity) {
                $index = $this->createProductPriceGroupingIndex($priceProductStoreEntity, $priceProductEntity);
                $productPriceCollection[$index] = $this->mapProductPriceTransfer(
                    $priceProductStoreEntity,
                    $priceProductEntity
                );
            }
        }

        return $productPriceCollection;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapProductPriceTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        SpyPriceProduct $priceProductEntity
    ) {

        $currencyTransfer = $this->currencyFacade
            ->getByIdCurrency($priceProductStoreEntity->getFkCurrency());

        $moneyValueTransfer = (new MoneyValueTransfer())
            ->fromArray($priceProductStoreEntity->toArray(), true)
            ->setIdEntity($priceProductStoreEntity->getPrimaryKey())
            ->setNetAmount($priceProductStoreEntity->getNetPrice())
            ->setGrossAmount($priceProductStoreEntity->getGrossPrice())
            ->setCurrency($currencyTransfer);

        $priceTypeTransfer = (new PriceTypeTransfer())
            ->fromArray($priceProductEntity->getPriceType()->toArray(), true);

        return (new PriceProductTransfer())
            ->fromArray($priceProductEntity->toArray(), true)
            ->setIdProductAbstract($priceProductEntity->getFkProductAbstract())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setMoneyValue($moneyValueTransfer);
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function getAbstractSku($sku)
    {
        $abstractSku = $sku;
        if ($this->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        return $abstractSku;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findProductConcretePricesBySku($sku)
    {
        $productConcretePriceEntities = $this->queryContainer
            ->queryPricesForProductConcreteBySku($sku)
            ->find();

        return $this->mapPriceProductTransferCollectionForProductConcrete($productConcretePriceEntities);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct[] $priceProductEntities
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function mapPriceProductTransferCollectionForProductConcrete($priceProductEntities)
    {
        $productPriceCollection = [];
        foreach ($priceProductEntities as $priceProductEntity) {
            foreach ($priceProductEntity->getPriceProductStores() as $priceProductStoreEntity) {
                $index = $this->createProductPriceGroupingIndex($priceProductStoreEntity, $priceProductEntity);
                $productPriceCollection[$index] = $this->mapProductPriceTransfer(
                    $priceProductStoreEntity,
                    $priceProductEntity
                );
            }
        }

        return $productPriceCollection;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findProductAbstractPricesById($idProductAbstract)
    {
        $productAbstractPriceEntities = $this->queryContainer
            ->queryPricesForProductAbstractById($idProductAbstract)
            ->find();

        return $this->mapPriceProductTransferCollectionForProductAbstract($productAbstractPriceEntities);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findProductConcretePricesById($idProductConcrete)
    {
        $productAbstractPriceEntities = $this->queryContainer
            ->queryPricesForProductConcreteById($idProductConcrete)
            ->find();

        return $this->mapPriceProductTransferCollectionForProductConcrete($productAbstractPriceEntities);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return string
     */
    protected function createProductPriceGroupingIndex(
        SpyPriceProductStore $priceProductStoreEntity,
        SpyPriceProduct $priceProductEntity
    ) {
        return implode(
            '-',
            [
                $priceProductStoreEntity->getFkStore(),
                $priceProductStoreEntity->getFkCurrency(),
                $priceProductEntity->getPriceType()->getName(),
                $priceProductEntity->getPriceType()->getPriceModeConfiguration(),
            ]
        );
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
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyFromFilter(PriceFilterTransfer $priceFilterTransfer)
    {
        if ($priceFilterTransfer->getCurrencyIsoCode()) {
            return $this->currencyFacade->fromIsoCode($priceFilterTransfer->getCurrencyIsoCode());
        }

        return $this->currencyFacade->getDefaultCurrencyForCurrentStore();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreFromFilter(PriceFilterTransfer $priceFilterTransfer)
    {
        if ($priceFilterTransfer->getStoreName()) {
            return $this->storeFacade->getStoreByName($priceFilterTransfer->getStoreName());
        }

        return $this->storeFacade->getCurrentStore();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return string
     */
    protected function getPriceModeFromFilter(PriceFilterTransfer $priceFilterTransfer)
    {
        $priceMode = $priceFilterTransfer->getPriceMode();
        if (!$priceMode) {
            $priceMode = $this->priceFacade->getDefaultPriceMode();
        }
        return $priceMode;
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
     * @param int $idAbstractProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);

        $priceProductEntity = $this->queryContainer
            ->queryPricesForProductAbstractById($idAbstractProduct)
            ->filterByPriceType($priceTypeName)
            ->findOne();


        return $priceTransfer;
    }
}
