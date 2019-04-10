<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
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
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleListMapperInterface
     */
    protected $priceProductScheduleListMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleListMapperInterface $priceProductScheduleListMapper
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
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function mapPriceProductScheduleEntityToPriceProductScheduleTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = $this->mapPriceProductScheduleEntityToPriceProductTransfer($priceProductScheduleEntity);

        $priceProductScheduleListTransfer = $this->priceProductScheduleListMapper
            ->mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer($priceProductScheduleEntity->getPriceProductScheduleList());

        return (new PriceProductScheduleTransfer())
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
    public function mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities): array
    {
        $productPriceScheduleCollection = [];

        foreach ($priceProductScheduleEntities as $priceProductScheduleEntity) {
            $productPriceScheduleCollection[] = $this->mapPriceProductScheduleEntityToPriceProductScheduleTransfer($priceProductScheduleEntity);
        }

        return $productPriceScheduleCollection;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductScheduleEntityToPriceProductTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity
    ): PriceProductTransfer {
        $moneyValueTransfer = $this->mapPriceProductScheduleEntityToMoneyValueTransfer($priceProductScheduleEntity);

        $priceTypeTransfer = $this->mapPriceTypeEntityToPriceTypeTransfer($priceProductScheduleEntity->getPriceType());

        $priceProductTransfer = (new PriceProductTransfer())
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($this->mapPriceProductScheduleEntityToPriceProductDimensionTransfer($priceProductScheduleEntity));

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
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapPriceProductScheduleEntityToMoneyValueTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity
    ): MoneyValueTransfer {
        $currencyTransfer = $this->currencyFacade
            ->getByIdCurrency($priceProductScheduleEntity->getFkCurrency());

        return (new MoneyValueTransfer())
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setNetAmount($priceProductScheduleEntity->getNetPrice())
            ->setGrossAmount($priceProductScheduleEntity->getGrossPrice())
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType $spyPriceType
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    protected function mapPriceTypeEntityToPriceTypeTransfer(SpyPriceType $spyPriceType): PriceTypeTransfer
    {
        return (new PriceTypeTransfer())
            ->fromArray($spyPriceType->toArray(), true);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function mapPriceProductScheduleEntityToPriceProductDimensionTransfer(
        SpyPriceProductSchedule $priceProductScheduleEntity
    ): PriceProductDimensionTransfer {

        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->fromArray(
                $priceProductScheduleEntity->getVirtualColumns(),
                true
            );

        return $priceProductDimensionTransfer;
    }
}
