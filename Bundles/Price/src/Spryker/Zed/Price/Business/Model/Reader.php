<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Orm\Zed\Price\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Price\Persistence\SpyPriceProduct;
use Orm\Zed\Price\Persistence\SpyPriceProductStore;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Shared\Price\PriceMode;
use Spryker\Zed\Price\Business\Exception\MissingPriceException;
use Spryker\Zed\Price\Dependency\Facade\PriceToCurrencyInterface;
use Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface;
use Spryker\Zed\Price\Persistence\PriceQueryContainerInterface;
use Spryker\Zed\Price\PriceConfig;

class Reader implements ReaderInterface
{

    const PRICE_TYPE_UNKNOWN = 'price type unknown: ';
    const SKU_UNKNOWN = 'sku unknown';
    const COL_GROSS_PRICE = 'gross_price';
    const COL_NET_PRICE = 'net_price';

    /**
     * @var \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Price\PriceConfig
     */
    protected $priceConfig;

    /**
     * @var array
     */
    protected $priceTypeEntityByNameCache = [];

    /**
     * @var \Spryker\Zed\Price\Dependency\Facade\PriceToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface $productFacade
     * @param \Spryker\Zed\Price\PriceConfig $priceConfig
     * @param \Spryker\Zed\Price\Dependency\Facade\PriceToCurrencyInterface $currencyFacade
     */
    public function __construct(
        PriceQueryContainerInterface $queryContainer,
        PriceToProductInterface $productFacade,
        PriceConfig $priceConfig,
        PriceToCurrencyInterface $currencyFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->priceConfig = $priceConfig;
        $this->currencyFacade = $currencyFacade;
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

        /** @var \Orm\Zed\Price\Persistence\SpyPriceType $priceTypeEntity */
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
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName, $currencyIsoCode, $priceMode)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);

        $currencyTransfer = $this->currencyFacade->fromIsoCode($currencyIsoCode);

        $productPrice = $this->getProductPrice($sku, $priceTypeName, $currencyTransfer->getIdCurrency());

        if ($priceMode == PriceMode::PRICE_MODE_NET) {
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
     * @return \Orm\Zed\Price\Persistence\SpyPriceType
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
     * @param string $priceTypeName
     * @param string $currencyIsoCode
     * @param string $priceMode
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceTypeName, $currencyIsoCode, $priceMode)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);

        if (!$this->hasPriceType($priceTypeName)) {
            return false;
        }

        $currencyTransfer = $this->currencyFacade->fromIsoCode($currencyIsoCode);
        $idCurrency = $currencyTransfer->getIdCurrency();

        if ($this->hasPriceForProductConcrete($sku, $priceTypeName, $idCurrency) || $this->hasPriceForProductAbstract($sku, $priceTypeName, $idCurrency)) {
            return true;
        }

        if ($this->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            if ($this->hasProductAbstract($abstractSku) && $this->hasPriceForProductAbstract($abstractSku, $priceType, $idCurrency)) {
                return true;
            }
        }

        return false;
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

        if ($this->hasPriceForProductConcrete($sku, $priceType, $idCurrency)) {
            return $this->queryContainer
                ->queryPriceEntityForProductConcrete($sku, $priceType, $idCurrency)
                ->findOne()
                ->getIdPriceProduct();
        }

        if (!$this->hasPriceForProductAbstract($sku, $priceType, $idCurrency)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        return $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType, $idCurrency)
            ->findOne()
            ->getIdPriceProduct();
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     *
     * @throws \Spryker\Zed\Price\Business\Exception\MissingPriceException
     *
     * @return array
     */
    protected function getProductPrice($sku, $priceType, $idCurrency)
    {
        $priceProductConcrete = $this->getPriceForProductConcrete($sku, $priceType, $idCurrency);
        if ($priceProductConcrete !== null) {
            return $priceProductConcrete;
        }

        $priceProductAbstract = $this->getPriceForProductAbstract($sku, $priceType, $idCurrency);
        if ($priceProductAbstract !== null) {
            return $priceProductAbstract;
        }

        if ($this->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            $priceProductAbstract = $this->getPriceForProductAbstract($abstractSku, $priceType, $idCurrency);

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
     *
     * @return bool
     */
    protected function hasPriceForProductConcrete($sku, $priceType, $idCurrency)
    {
        $productConcrete = $this->queryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceType, $idCurrency)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        return $productConcrete !== null;
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     *
     * @return bool
     */
    protected function hasPriceForProductAbstract($sku, $priceType, $idCurrency)
    {
        $productAbstract = $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType, $idCurrency)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        return $productAbstract !== null;
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param string $idCurrency
     *
     * @return array
     */
    protected function getPriceForProductConcrete($sku, $priceType, $idCurrency)
    {
        return $this->queryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceType, $idCurrency)
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, static::COL_GROSS_PRICE)
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, static::COL_NET_PRICE)
            ->setFormatter(ArrayFormatter::class)
            ->findOne();
    }

    /**
     * @param string $sku
     * @param string $priceType
     * @param int $idCurrency
     *
     * @return array
     */
    protected function getPriceForProductAbstract($sku, $priceType, $idCurrency)
    {
        return $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType, $idCurrency)
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
     * @param \Orm\Zed\Price\Persistence\SpyPriceProduct[] $priceProductEntities
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
     * @param \Orm\Zed\Price\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\Price\Persistence\SpyPriceProduct $priceProductEntity
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
     * @param \Orm\Zed\Price\Persistence\SpyPriceProduct[] $priceProductEntities
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
     * @param \Orm\Zed\Price\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\Price\Persistence\SpyPriceProduct $priceProductEntity
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

}
