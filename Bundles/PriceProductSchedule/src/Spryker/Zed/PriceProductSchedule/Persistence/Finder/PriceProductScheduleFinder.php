<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Finder;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface;

class PriceProductScheduleFinder implements PriceProductScheduleFinderInterface
{
    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $priceProductScheduleQuery;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface
     */
    protected $priceProductScheduleMapper;

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface $priceProductScheduleMapper
     */
    public function __construct(SpyPriceProductScheduleQuery $priceProductScheduleQuery, PriceProductScheduleMapperInterface $priceProductScheduleMapper)
    {
        $this->priceProductScheduleQuery = $priceProductScheduleQuery;
        $this->priceProductScheduleMapper = $priceProductScheduleMapper;
    }

    /**
     * @module Currency
     * @module PriceProduct
     * @module Store
     * @module Product
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer $priceProductScheduleCriteriaFilterTransfer
     *
     * @return int
     */
    public function findCountPriceProductScheduleByCriteriaFilter(
        PriceProductScheduleCriteriaFilterTransfer $priceProductScheduleCriteriaFilterTransfer
    ): int {
        $query = $this->priceProductScheduleQuery
            ->joinWithCurrency()
            ->useCurrencyQuery()
            ->filterByCode($priceProductScheduleCriteriaFilterTransfer->getCurrencyCode())
            ->endUse()
            ->joinWithPriceType()
            ->usePriceTypeQuery()
            ->filterByName($priceProductScheduleCriteriaFilterTransfer->getPriceTypeName())
            ->endUse()
            ->joinWithStore()
            ->useStoreQuery()
            ->filterByName($priceProductScheduleCriteriaFilterTransfer->getStoreName())
            ->endUse()
            ->joinWithPriceProductScheduleList()
            ->filterByNetPrice($priceProductScheduleCriteriaFilterTransfer->getNetAmount())
            ->filterByGrossPrice($priceProductScheduleCriteriaFilterTransfer->getGrossAmount())
            ->filterByActiveFrom(new DateTime($priceProductScheduleCriteriaFilterTransfer->getActiveFrom()))
            ->filterByActiveTo(new DateTime($priceProductScheduleCriteriaFilterTransfer->getActiveTo()));

        if ($priceProductScheduleCriteriaFilterTransfer->getSkuProductAbstract() !== null) {
            $query
                ->joinWithProductAbstract()
                ->useProductAbstractQuery()
                ->filterBySku($priceProductScheduleCriteriaFilterTransfer->getSkuProductAbstract())
                ->endUse();
        }

        if ($priceProductScheduleCriteriaFilterTransfer->getSkuProduct() !== null) {
            $query
                ->joinWithProduct()
                ->useProductQuery()
                ->filterBySku($priceProductScheduleCriteriaFilterTransfer->getSkuProduct())
                ->endUse();
        }

        return $query->count();
    }

    /**
     * @param int $idPriceProductSchedule
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer|null
     */
    public function findPriceProductScheduleById(int $idPriceProductSchedule): ?PriceProductScheduleTransfer
    {
        $priceProductScheduleEntity = $this->priceProductScheduleQuery
            ->filterByIdPriceProductSchedule($idPriceProductSchedule)
            ->findOne();

        if ($priceProductScheduleEntity === null) {
            return null;
        }

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntityToPriceProductScheduleTransfer($priceProductScheduleEntity, new PriceProductScheduleTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return bool
     */
    public function isPriceProductScheduleUnique(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): bool {
        $priceProductScheduleTransfer->requirePriceProduct()
            ->getPriceProduct()
            ->requireMoneyValue();

        $priceProductScheduleEntity = $this->priceProductScheduleMapper
            ->mapPriceProductScheduleTransferToPriceProductScheduleEntity($priceProductScheduleTransfer, new SpyPriceProductSchedule());

        $priceProductScheduleQuery = $this->priceProductScheduleQuery
            ->filterByActiveFrom($priceProductScheduleEntity->getActiveFrom())
            ->filterByActiveTo($priceProductScheduleEntity->getActiveTo())
            ->filterByNetPrice($priceProductScheduleEntity->getNetPrice())
            ->filterByGrossPrice($priceProductScheduleEntity->getGrossPrice())
            ->filterByFkCurrency($priceProductScheduleEntity->getFkCurrency())
            ->filterByFkStore($priceProductScheduleEntity->getFkStore())
            ->filterByFkPriceType($priceProductScheduleEntity->getFkPriceType())
            ->filterByIdPriceProductSchedule($priceProductScheduleEntity->getIdPriceProductSchedule(), Criteria::NOT_EQUAL);

        $priceProductScheduleQuery = $this->addProductIdentifierToUniqueQuery(
            $priceProductScheduleEntity,
            $priceProductScheduleQuery
        );

        return $priceProductScheduleQuery->count() === 0;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function addProductIdentifierToUniqueQuery(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        SpyPriceProductScheduleQuery $priceProductScheduleQuery
    ): SpyPriceProductScheduleQuery {
        $idProduct = $priceProductScheduleEntity->getFkProduct();
        if ($idProduct !== null) {
            return $priceProductScheduleQuery->filterByFkProduct($idProduct);
        }

        $idProductAbstract = $priceProductScheduleEntity->getFkProductAbstract();
        if ($idProductAbstract !== null) {
            return $priceProductScheduleQuery->filterByFkProductAbstract($idProductAbstract);
        }

        return $priceProductScheduleQuery;
    }
}
