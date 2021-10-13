<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Disable;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class PriceProductScheduleDisableFinder implements PriceProductScheduleDisableFinderInterface
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
    public function __construct(
        SpyPriceProductScheduleQuery $priceProductScheduleQuery,
        PriceProductScheduleMapperInterface $priceProductScheduleMapper
    ) {
        $this->priceProductScheduleQuery = $priceProductScheduleQuery;
        $this->priceProductScheduleMapper = $priceProductScheduleMapper;
    }

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToDisable(): array
    {
        $priceProductScheduleEntities = $this->priceProductScheduleQuery
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(true)
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO))
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return bool
     */
    public function isScheduledPriceForSwitchExists(PriceProductScheduleTransfer $priceProductScheduleTransfer): bool
    {
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProductOrFail();
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        /** @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery */
        $priceProductScheduleQuery = $this->priceProductScheduleQuery
            ->filterByFkCurrency($moneyValueTransfer->getCurrencyOrFail()->getIdCurrencyOrFail())
            ->filterByFkStore($moneyValueTransfer->getStoreOrFail()->getIdStoreOrFail())
            ->filterByFkPriceType($priceProductTransfer->getFkPriceType())
            ->where(sprintf('%s > now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO));

        $priceProductScheduleQuery = $this->addProductIdentifierToIsScheduledPriceForSwitchExists(
            $priceProductScheduleQuery,
            $priceProductTransfer
        );

        return $priceProductScheduleQuery->exists();
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function addProductIdentifierToIsScheduledPriceForSwitchExists(
        SpyPriceProductScheduleQuery $priceProductScheduleQuery,
        PriceProductTransfer $priceProductTransfer
    ): SpyPriceProductScheduleQuery {
        if ($priceProductTransfer->getIdProduct()) {
            return $priceProductScheduleQuery->filterByFkProduct($priceProductTransfer->getIdProductOrFail());
        }

        return $priceProductScheduleQuery->filterByFkProductAbstract($priceProductTransfer->getIdProductAbstractOrFail());
    }

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToDisableByIdProductAbstract(int $idProductAbstract): array
    {
        $priceProductScheduleEntities = $this->priceProductScheduleQuery
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByIsCurrent(true)
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO))
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param int $idProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToDisableByIdProductConcrete(int $idProductConcrete): array
    {
        $priceProductScheduleEntities = $this->priceProductScheduleQuery
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByFkProduct($idProductConcrete)
            ->filterByIsCurrent(true)
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO))
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findSimilarPriceProductSchedulesToDisable(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $priceProductTransfer
            ->requireMoneyValue()
            ->requirePriceType();

        $priceProductScheduleEntities = $this->priceProductScheduleQuery
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(true)
            ->filterByFkStore($priceProductTransfer->getMoneyValue()->getFkStore())
            ->filterByFkCurrency($priceProductTransfer->getMoneyValue()->getFkCurrency())
            ->filterByFkPriceType($priceProductTransfer->getPriceType()->getIdPriceType())
            ->filterByFkProduct($priceProductTransfer->getIdProduct())
            ->filterByFkProductAbstract($priceProductTransfer->getIdProductAbstract())
            ->filterByIdPriceProductSchedule(
                $priceProductScheduleTransfer->getIdPriceProductSchedule(),
                Criteria::NOT_EQUAL
            )
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }
}
