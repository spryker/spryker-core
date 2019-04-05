<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

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
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface $productFacade
     */
    public function __construct(
        PriceProductScheduleToCurrencyFacadeInterface $currencyFacade,
        PriceProductScheduleToProductFacadeInterface $productFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function mapPriceProductScheduleEntityToTransfer(SpyPriceProductSchedule $priceProductScheduleEntity
    ): PriceProductScheduleTransfer
    {
        $priceProductTransfer = $this->mapPriceProductTransfer($priceProductScheduleEntity);

        $priceProductScheduleListTransfer = (new PriceProductSchedulelistTransfer())
            ->fromArray($priceProductScheduleEntity->getPriceProductScheduleList()->toArray(), true);

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
    public function mapPriceProductScheduleTransferToEntity(
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        SpyPriceProductSchedule $priceProductScheduleEntity
    ): SpyPriceProductSchedule {
        return $priceProductScheduleEntity
            ->setFkCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkCurrency())
            ->setFkStore($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkStore())
            ->setFkPriceType($priceProductScheduleTransfer->getPriceProduct()->getFkPriceType())
            ->setFkProduct($priceProductScheduleTransfer->getPriceProduct()->getIdProduct())
            ->setFkProductAbstract($priceProductScheduleTransfer->getPriceProduct()->getIdProductAbstract())
            ->setFkPriceProductScheduleList($priceProductScheduleTransfer->getPriceProductScheduleList()->getIdPriceProductScheduleList())
            ->setNetPrice($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getNetAmount())
            ->setGrossPrice($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getGrossAmount())
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
            $productPriceScheduleCollection[] = $this->mapPriceProductScheduleEntityToTransfer($priceProductScheduleEntity);
        }

        return $productPriceScheduleCollection;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductTransfer(SpyPriceProductSchedule $priceProductScheduleEntity): PriceProductTransfer
    {
        $moneyValueTransfer = $this->mapMoneyValueTransfer($priceProductScheduleEntity);

        $priceTypeTransfer = $this->mapPriceTypeTransfer($priceProductScheduleEntity->getPriceType());

        $priceProductTransfer = (new PriceProductTransfer())
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($this->getPriceProductDimensionTransfer($priceProductScheduleEntity));

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
    protected function mapMoneyValueTransfer(SpyPriceProductSchedule $priceProductScheduleEntity): MoneyValueTransfer
    {
        $currencyTransfer = $this->currencyFacade
            ->getByIdCurrency($priceProductScheduleEntity->getFkCurrency());

        return (new MoneyValueTransfer())
            ->fromArray($priceProductScheduleEntity->toArray(), true)
            ->setIdEntity($priceProductScheduleEntity->getPrimaryKey())
            ->setNetAmount($priceProductScheduleEntity->getNetPrice())
            ->setGrossAmount($priceProductScheduleEntity->getGrossPrice())
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\Base\SpyPriceType $spyPriceType
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    protected function mapPriceTypeTransfer(SpyPriceType $spyPriceType): PriceTypeTransfer
    {
        return (new PriceTypeTransfer())
            ->fromArray($spyPriceType->toArray(), true)
            ->setIdPriceType($spyPriceType->getIdPriceType())
            ->setName($spyPriceType->getName())
            ->setPriceModeConfiguration($spyPriceType->getPriceModeConfiguration());
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function getPriceProductDimensionTransfer(
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
