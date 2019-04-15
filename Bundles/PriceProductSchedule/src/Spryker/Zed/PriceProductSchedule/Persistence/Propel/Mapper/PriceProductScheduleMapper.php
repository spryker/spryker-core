<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface;

class PriceProductScheduleMapper implements PriceProductScheduleMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface
     */
    protected $priceProductScheduleListMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface $priceProductScheduleListMapper
     */
    public function __construct(
        PriceProductScheduleToCurrencyFacadeInterface $currencyFacade,
        PriceProductScheduleToProductFacadeInterface $productFacade,
        PriceProductScheduleListMapperInterface $priceProductScheduleListMapper
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->productFacade = $productFacade;
        $this->priceProductScheduleListMapper = $priceProductScheduleListMapper;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function mapPriceProductScheduleEntityToPriceProductScheduleTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = $this->mapPriceProductScheduleEntityToPriceProductTransfer(
            $priceProductScheduleEntity,
            new PriceProductTransfer()
        );

        $priceProductScheduleListTransfer = $this->priceProductScheduleListMapper
            ->mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
                $priceProductScheduleEntity->getPriceProductScheduleList(),
                new PriceProductScheduleListTransfer()
            );

        return $priceProductScheduleTransfer
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setPriceProduct($priceProductTransfer)
            ->setPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule
     */
    public function mapPriceProductScheduleTransferToPriceProductScheduleEntity(
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        SpyPriceProductSchedule $priceProductScheduleEntity
    ): SpyPriceProductSchedule {
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        return $priceProductScheduleEntity
            ->setFkCurrency($moneyValueTransfer->getFkCurrency())
            ->setFkStore($moneyValueTransfer->getFkStore())
            ->setFkPriceType($priceProductTransfer->getPriceType()->getIdPriceType())
            ->setFkProduct($priceProductTransfer->getIdProduct())
            ->setFkProductAbstract($priceProductTransfer->getIdProductAbstract())
            ->setFkPriceProductScheduleList($priceProductScheduleTransfer->getPriceProductScheduleList()->getIdPriceProductScheduleList())
            ->setNetPrice($moneyValueTransfer->getNetAmount())
            ->setGrossPrice($moneyValueTransfer->getGrossAmount())
            ->setActiveFrom($priceProductScheduleTransfer->getActiveFrom())
            ->setActiveTo($priceProductScheduleTransfer->getActiveTo())
            ->setIsCurrent($priceProductScheduleTransfer->getIsCurrent());
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[] $priceProductScheduleEntities
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers(array $priceProductScheduleEntities): array
    {
        $productPriceScheduleCollection = [];

        foreach ($priceProductScheduleEntities as $priceProductScheduleEntity) {
            $productPriceScheduleCollection[] = $this->mapPriceProductScheduleEntityToPriceProductScheduleTransfer(
                $priceProductScheduleEntity,
                new PriceProductScheduleTransfer()
            );
        }

        return $productPriceScheduleCollection;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductScheduleEntityToPriceProductTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $moneyValueTransfer = $this->mapPriceProductScheduleEntityToMoneyValueTransfer(
            $priceProductScheduleEntity,
            new MoneyValueTransfer()
        );

        $priceTypeTransfer = $this->mapPriceTypeEntityToPriceTypeTransfer(
            $priceProductScheduleEntity->getPriceType(),
            new PriceTypeTransfer()
        );

        $priceProductDimensionTransfer = $this->mapPriceProductScheduleEntityToPriceProductDimensionTransfer(
            $priceProductScheduleEntity,
            new PriceProductDimensionTransfer()
        );

        $priceProductTransfer
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer);

        if ($priceProductScheduleEntity->getFkProduct()) {
            $productConcreteTransfer = $this->productFacade->findProductConcreteById($priceProductScheduleEntity->getFkProduct());

            $priceProductTransfer->setIdProduct($productConcreteTransfer->getIdProductConcrete());
            $priceProductTransfer->setSkuProduct($productConcreteTransfer->getSku());
            $priceProductTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());
            $priceProductTransfer->setSkuProductAbstract($productConcreteTransfer->getAbstractSku());
        }

        if ($priceProductScheduleEntity->getFkProductAbstract()) {
            $productAbstractTransfer = $this->productFacade->findProductAbstractById($priceProductScheduleEntity->getFkProductAbstract());

            $priceProductTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
            $priceProductTransfer->setSkuProductAbstract($productAbstractTransfer->getSku());
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapPriceProductScheduleEntityToMoneyValueTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        $currencyTransfer = $this->currencyFacade
            ->getByIdCurrency($priceProductScheduleEntity->getFkCurrency());

        return $moneyValueTransfer
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setNetAmount($priceProductScheduleEntity->getNetPrice())
            ->setGrossAmount($priceProductScheduleEntity->getGrossPrice())
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType $spyPriceType
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    protected function mapPriceTypeEntityToPriceTypeTransfer(
        SpyPriceType $spyPriceType,
        PriceTypeTransfer $priceTypeTransfer
    ): PriceTypeTransfer {
        return $priceTypeTransfer
            ->fromArray($spyPriceType->toArray(), true);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function mapPriceProductScheduleEntityToPriceProductDimensionTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        PriceProductDimensionTransfer $priceProductDimensionTransfer
    ): PriceProductDimensionTransfer {
        return $priceProductDimensionTransfer
            ->fromArray($priceProductScheduleEntity->getVirtualColumns(), true);
    }
}
