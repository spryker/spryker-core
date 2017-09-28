<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Model;

use Exception;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Spryker\Zed\Price\Business\Exception\MissingPriceException;
use Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface;
use Spryker\Zed\Price\Persistence\PriceQueryContainerInterface;
use Spryker\Zed\Price\PriceConfig;

class Reader implements ReaderInterface
{

    const PRICE_TYPE_UNKNOWN = 'price type unknown: ';
    const SKU_UNKNOWN = 'sku unknown';

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
    protected static $priceTypeEntityByNameCache = [];

    /**
     * @param \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface $productFacade
     * @param \Spryker\Zed\Price\PriceConfig $priceConfig
     */
    public function __construct(
        PriceQueryContainerInterface $queryContainer,
        PriceToProductInterface $productFacade,
        PriceConfig $priceConfig
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->priceConfig = $priceConfig;
    }

    /**
     * @return string[]
     */
    public function getPriceTypes()
    {
        $priceTypes = [];
        $priceTypeEntities = $this->queryContainer->queryAllPriceTypes()->find();

        /** @var \Orm\Zed\Price\Persistence\SpyPriceType $priceType */
        foreach ($priceTypeEntities as $priceType) {
            $priceTypes[] = $priceType->getName();
        }

        return $priceTypes;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);
        $priceEntity = $this->getPriceEntity($sku, $this->getPriceTypeByName($priceTypeName));

        return $priceEntity->getPrice();
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
     * @param int $idAbstractProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);
        $priceEntity = $this->queryContainer
            ->queryPriceProduct()
            ->filterByFkProductAbstract($idAbstractProduct)
            ->filterByPriceType($this->getPriceTypeByName($priceTypeName))
            ->findOne();

        if (!$priceEntity) {
            return null;
        }

        $priceTransfer = (new PriceProductTransfer());
        $priceTransfer
            ->fromArray($priceEntity->toArray(), true)
            ->setIdProductAbstract($idAbstractProduct)
            ->setPrice($priceEntity->getPrice())
            ->setPriceTypeName($priceTypeName);

        return $priceTransfer;
    }

    /**
     * @param int $idProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductConcretePrice($idProduct, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);
        $priceTypeEntity = $this->getPriceTypeByName($priceTypeName);

        $priceEntity = $this->queryContainer
            ->queryPriceProduct()
            ->filterByFkProduct($idProduct)
            ->filterByPriceType($priceTypeEntity)
            ->findOne();

        if (!$priceEntity) {
            $priceEntity = $this->queryContainer
                ->queryProductAbstractPriceByIdConcreteProduct($idProduct)
                ->filterByPriceType($priceTypeEntity)
                ->findOne();
        }

        if (!$priceEntity) {
            return null;
        }

        $priceTransfer = (new PriceProductTransfer())
            ->fromArray($priceEntity->toArray(), true)
            ->setIdProduct($idProduct)
            ->setPrice($priceEntity->getPrice())
            ->setPriceTypeName($priceTypeName);

        return $priceTransfer;
    }

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

        return clone self::$priceTypeEntityByNameCache[$priceTypeName];
    }

    /**
     * @param string $priceTypeName
     *
     * @return bool
     */
    protected function hasPriceType($priceTypeName)
    {
        if (!isset(self::$priceTypeEntityByNameCache[$priceTypeName])) {
            $priceTypeEntity = $this->queryContainer->queryPriceType($priceTypeName)->findOne();

            if ($priceTypeEntity === null) {
                return false;
            }

            self::$priceTypeEntityByNameCache[$priceTypeName] = $priceTypeEntity;
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

        $priceType = $this->getPriceTypeByName($priceTypeName);
        if ($this->hasPriceForProductConcrete($sku, $priceType) || $this->hasPriceForProductAbstract($sku, $priceType)) {
            return true;
        }

        if ($this->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            if ($this->hasProductAbstract($abstractSku) && $this->hasPriceForProductAbstract($abstractSku, $priceType)) {
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
     *
     * @return int
     */
    public function getProductPriceIdBySku($sku, $priceTypeName)
    {
        $priceType = $this->getPriceTypeByName($priceTypeName);

        if ($this->hasPriceForProductConcrete($sku, $priceType)) {
            return $this->queryContainer
                ->queryPriceEntityForProductConcrete($sku, $priceType)
                ->findOne()
                ->getIdPriceProduct();
        }

        if (!$this->hasPriceForProductAbstract($sku, $priceType)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        return $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType)
            ->findOne()
            ->getIdPriceProduct();
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Spryker\Zed\Price\Business\Exception\MissingPriceException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function getPriceEntity($sku, SpyPriceType $priceType)
    {
        $priceProductConcreteEntity = $this->getPriceEntityForProductConcrete($sku, $priceType);

        if ($priceProductConcreteEntity) {
            return $priceProductConcreteEntity;
        }

        $priceProductAbstractEntity = $this->getPriceEntityForProductAbstract($sku, $priceType);

        if ($priceProductAbstractEntity) {
            return $priceProductAbstractEntity;
        }

        if ($this->hasProductConcrete($sku)) {
            $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
            $priceProductAbstractEntity = $this->getPriceEntityForProductAbstract($abstractSku, $priceType);

            if ($priceProductAbstractEntity) {
                return $priceProductAbstractEntity;
            }
        }

        throw new MissingPriceException(sprintf(
            'Price not found for product with SKU: %s!',
            $sku
        ));
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return bool
     */
    protected function hasPriceForProductConcrete($sku, SpyPriceType $priceType)
    {
        $productConcrete = $this->queryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceType)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        return $productConcrete !== null;
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return bool
     */
    protected function hasPriceForProductAbstract($sku, $priceType)
    {
        $productAbstract = $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType)
            ->select([SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT])
            ->findOne();

        return $productAbstract !== null;
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function getPriceEntityForProductConcrete($sku, $priceType)
    {
        return $this->queryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceType)
            ->findOne();
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function getPriceEntityForProductAbstract($sku, $priceType)
    {
        return $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType)
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
        $priceTransfers = [];
        foreach ($priceProductEntities as $priceProductEntity) {
            $priceTypeName = $priceProductEntity->getPriceType()->getName();

            $priceProductTransfer = new PriceProductTransfer();
            $priceProductTransfer
                ->fromArray($priceProductEntity->toArray(), true)
                ->setIdProductAbstract($priceProductEntity->getFkProductAbstract())
                ->setPriceTypeName($priceTypeName);

            $priceTransfers[$priceTypeName] = $priceProductTransfer;
        }

        return $priceTransfers;
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
        $priceTransfers = [];
        foreach ($priceProductEntities as $priceProductEntity) {
            $priceTypeName = $priceProductEntity->getPriceType()->getName();

            $priceProductTransfer = new PriceProductTransfer();
            $priceProductTransfer
                ->fromArray($priceProductEntity->toArray(), true)
                ->setIdProduct($priceProductEntity->getFkProduct())
                ->setPriceTypeName($priceTypeName);

            $priceTransfers[$priceTypeName] = $priceProductTransfer;
        }

        return $priceTransfers;
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

}
