<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductMapper implements PriceProductMapperInterface
{
    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifier;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface
     */
    protected $priceProductTypeMapper;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface $priceProductTypeMapper
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $config
     */
    public function __construct(
        PriceProductToCurrencyFacadeInterface $currencyFacade,
        ProductPriceTypeMapperInterface $priceProductTypeMapper,
        PriceProductToPriceFacadeInterface $priceFacade,
        PriceProductConfig $config
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->priceProductTypeMapper = $priceProductTypeMapper;
        $this->priceFacade = $priceFacade;
        $this->config = $config;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapProductPriceTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        SpyPriceProduct $priceProductEntity
    ) {

        $moneyValueTransfer = $this->mapMoneyValueTransfer($priceProductStoreEntity);
        $priceTypeTransfer = $this->priceProductTypeMapper->mapFromEntity($priceProductEntity->getPriceType());

        return (new PriceProductTransfer())
            ->fromArray($priceProductEntity->toArray(), true)
            ->setIdProduct($priceProductEntity->getFkProduct())
            ->setIdProductAbstract($priceProductEntity->getFkProductAbstract())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setMoneyValue($moneyValueTransfer);
    }


    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        if (!static::$grossPriceModeIdentifier) {
            static::$grossPriceModeIdentifier = $this->priceFacade->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifier;
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapMoneyValueTransfer(SpyPriceProductStore $priceProductStoreEntity)
    {
        $currencyTransfer = $this->currencyFacade
            ->getByIdCurrency($priceProductStoreEntity->getFkCurrency());

        return (new MoneyValueTransfer())
            ->fromArray($priceProductStoreEntity->toArray(), true)
            ->setIdEntity($priceProductStoreEntity->getPrimaryKey())
            ->setNetAmount($priceProductStoreEntity->getNetPrice())
            ->setGrossAmount($priceProductStoreEntity->getGrossPrice())
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[] $priceProductStoreEntities
     * @param PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStoreEntitiesToPriceProductTransfers(
        $priceProductStoreEntities,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $productPriceCollection = [];
        foreach ($priceProductStoreEntities as $priceProductStoreEntity) {
            $index = $this->createProductPriceGroupingIndex($priceProductStoreEntity);
            $productPriceCollection[$index] = $this->mapPriceProductStoreEntityToTransfer(
                $priceProductStoreEntity,
                $priceProductCriteriaTransfer
            );
        }

        return $productPriceCollection;
    }


    /**
     * @param SpyPriceProductStore $priceProductStoreEntity
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapPriceProductStoreEntityToTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): PriceProductTransfer {

        $priceProductEntity = $priceProductStoreEntity->getPriceProduct();

        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setName($priceProductEntity->getPriceType()->getName())
            ->setPriceModeConfiguration($priceProductEntity->getPriceType()->getPriceModeConfiguration());

        $moneyValueTransfer = $this->mapMoneyValueTransfer($priceProductStoreEntity);

        $priceProductDimensionTransfer = $this->getPriceProductDimensionTransfer(
            $priceProductStoreEntity,
            $priceProductCriteriaTransfer
        );

        return (new PriceProductTransfer())
            ->fromArray($priceProductEntity->toArray(), true)
            ->setIdProduct($priceProductEntity->getFkProduct())
            ->setIdProductAbstract($priceProductEntity->getFkProductAbstract())
            ->setPriceType($priceTypeTransfer)
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     *
     * @return string
     */
    protected function createProductPriceGroupingIndex(SpyPriceProductStore $priceProductStoreEntity)
    {
        $priceProductEntity = $priceProductStoreEntity->getPriceProduct();

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
     * @param SpyPriceProductStore $priceProductStoreEntity
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function getPriceProductDimensionTransfer(
        SpyPriceProductStore $priceProductStoreEntity,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): PriceProductDimensionTransfer {
        return (new PriceProductDimensionTransfer())
            ->setType($priceProductCriteriaTransfer->getPriceDimension()->getType())
            ->fromArray(
                $priceProductStoreEntity->getVirtualColumns(),
                true
            );
    }


}
