<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactory getFactory()
 */
class PriceProductScheduleRepository extends AbstractRepository implements PriceProductScheduleRepositoryInterface
{
    public const COL_PRODUCT_ID = 'product_id';
    public const COL_RESULT = 'result';

    /**
     * @param int $idPriceProductSchedule
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule|null
     */
    public function findByIdPriceProductSchedule(int $idPriceProductSchedule): ?SpyPriceProductSchedule
    {
        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIdPriceProductSchedule($idPriceProductSchedule)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[]
     */
    public function findPriceProductSchedulesToDisable(): array
    {
        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIsCurrent(true)
            ->filterByActiveTo(['max' => new DateTime()], Criteria::LESS_EQUAL)
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[]
     */
    public function findSimilarPriceProductSchedulesToDisable(PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array
    {
        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIsCurrent(true)
            ->filterByFkStore($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkStore())
            ->filterByFkCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkCurrency())
            ->filterByFkPriceType($priceProductScheduleTransfer->getPriceProduct()->getPriceType()->getIdPriceType())
            ->filterByFkProduct($priceProductScheduleTransfer->getPriceProduct()->getIdProduct())
            ->filterByFkProductAbstract($priceProductScheduleTransfer->getPriceProduct()->getIdProductAbstract())
            ->filterByIdPriceProductSchedule(
                $priceProductScheduleTransfer->getIdPriceProductSchedule(),
                Criteria::NOT_EQUAL
            )
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule[]
     */
    public function findPriceProductSchedulesToEnableByStore(StoreTransfer $storeTransfer): array
    {
        $queryWithConcatenatedFields = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->select([static::COL_PRODUCT_ID])
            ->addAsColumn(
                static::COL_PRODUCT_ID,
                sprintf(
                    'CONCAT(%s, \' \', %s, \' \', %s, \'_\', %s)',
                    SpyPriceProductScheduleTableMap::COL_FK_PRICE_TYPE,
                    SpyPriceProductScheduleTableMap::COL_FK_CURRENCY,
                    SpyPriceProductScheduleTableMap::COL_FK_PRODUCT,
                    SpyPriceProductScheduleTableMap::COL_FK_PRODUCT_ABSTRACT
                )
            )
            ->addAsColumn(
                static::COL_RESULT,
                sprintf(
                    'CONCAT(EXTRACT(epoch from now() - %s), \' \', EXTRACT(epoch from %s - now()), \' \', %s, \' \', %s, \' \', %s)',
                    SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM,
                    SpyPriceProductScheduleTableMap::COL_ACTIVE_TO,
                    SpyPriceProductScheduleTableMap::COL_NET_PRICE,
                    SpyPriceProductScheduleTableMap::COL_GROSS_PRICE,
                    SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE
                )
            )
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByActiveFrom(['max' => new DateTime()], Criteria::LESS_EQUAL)
            ->filterByActiveTo(['min' => new DateTime()], Criteria::GREATER_EQUAL);

        $filteredByMinResultQuery = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($queryWithConcatenatedFields, 'A', false)
            ->addAsColumn(static::COL_PRODUCT_ID, 'A.' . static::COL_PRODUCT_ID)
            ->addAsColumn('R', sprintf('min(%s)', 'A.' . static::COL_RESULT))
            ->groupBy(static::COL_PRODUCT_ID)
            ->limit($this->getFactory()->getConfig()->getApplyBatchSize());

        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($filteredByMinResultQuery, 'B', false)
            ->usePriceProductScheduleListQuery()
            ->filterByIsActive(true)
            ->endUse()
            ->filterByIsCurrent(false)
            ->where(SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE . ' = SUBSTRING(B.R from \'[0-9]+$\')::BIGINT')
            ->find()
            ->getData();
    }
}
