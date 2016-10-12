<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface;
use Spryker\Zed\Price\Persistence\PriceQueryContainerInterface;
use Spryker\Zed\Price\PriceConfig;

class Reader implements ReaderInterface
{

    const PRICE_TYPE_UNKNOWN = 'price type unknown: ';
    const NO_RESULT = 'no result';
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
    protected $priceTypeEntityByNameCache = [];

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
     * @return array
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
     * @param int $idAbstractProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function getProductAbstractPrice($idAbstractProduct, $priceTypeName = null)
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
    public function getProductConcretePrice($idProduct, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);
        $priceEntity = $this->queryContainer
            ->queryPriceProduct()
            ->filterByFkProduct($idProduct)
            ->filterByPriceType($this->getPriceTypeByName($priceTypeName))
            ->findOne();

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
        if (!isset($this->priceTypeEntityByNameCache[$priceTypeName])) {
            $priceTypeEntity = $this->queryContainer->queryPriceType($priceTypeName)->findOne();
            if ($priceTypeEntity === null) {
                throw new \Exception(self::PRICE_TYPE_UNKNOWN . $priceTypeName);
            }

            $this->priceTypeEntityByNameCache[$priceTypeName] = $priceTypeEntity;
        }

        return $this->priceTypeEntityByNameCache[$priceTypeName];
    }

    /**
     * TODO missing validation of dates
     *
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceTypeName = null)
    {
        $priceTypeName = $this->handleDefaultPriceType($priceTypeName);
        $priceType = $this->getPriceTypeByName($priceTypeName);

        if ($this->hasPriceForProductConcrete($sku, $priceType)
            || $this->hasPriceForProductAbstract($sku, $priceType)) {
            return true;
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

        return $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType)
            ->findOne()
            ->getIdPriceProduct();
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function getPriceEntity($sku, SpyPriceType $priceType)
    {
        if ($this->hasPriceForProductConcrete($sku, $priceType)) {
            return $this->getPriceEntityForProductConcrete($sku, $priceType);
        }
        if ($this->hasPriceForProductAbstract($sku, $priceType)) {
            return $this->getPriceEntityForProductAbstract($sku, $priceType);
        }
        $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        if (!$this->hasProductAbstract($sku)
            || !$this->hasPriceForProductAbstract($abstractSku, $priceType)
        ) {
            throw new \Exception(self::NO_RESULT);
        }

        return $this->getPriceEntityForProductAbstract($abstractSku, $priceType);
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return bool
     */
    protected function hasPriceForProductConcrete($sku, SpyPriceType $priceType)
    {
        $priceProductCount = $this->queryContainer
            ->queryPriceEntityForProductConcrete($sku, $priceType)
            ->count();

        return $priceProductCount > 0;
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return bool
     */
    protected function hasPriceForProductAbstract($sku, $priceType)
    {
        $priceProductCount = $this->queryContainer
            ->queryPriceEntityForProductAbstract($sku, $priceType)
            ->count();

        return $priceProductCount > 0;
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
        return $this->productFacade->getProductAbstractIdBySku($sku);
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

}
