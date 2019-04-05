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
    public function findSimilarPriceProductSchedulesToDisable(PriceProductScheduleTransfer $priceProductScheduleTransfer): array
    {
        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIsCurrent(true)
            ->filterByFkStore($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkStore())
            ->filterByFkCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkCurrency())
            ->filterByFkPriceType($priceProductScheduleTransfer->getPriceProduct()->getFkPriceType())
            ->filterByFkProduct($priceProductScheduleTransfer->getPriceProduct()->getIdProduct())
            ->filterByFkProductAbstract($priceProductScheduleTransfer->getPriceProduct()->getIdProductAbstract())
            ->filterByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule(), Criteria::NOT_EQUAL)
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
        // '
        // SELECT C.*
        // FROM (
        //    SELECT productID, min(RESULT) R
        //    FROM (
        //       SELECT CONCAT(fk_price_type, \' \', fk_currency, \' \', fk_product, \'_\', fk_product_abstract) AS productID,
        //              CONCAT(EXTRACT(epoch from now() - active_from), \' \', EXTRACT(epoch from active_to - now()),
        //                     \' \', net_price, \' \', gross_price, \' \', id_price_product_schedule) as RESULT
        //       FROM spy_price_product_schedule
        //       WHERE fk_store = 1
        //         AND now() >= spy_price_product_schedule.active_from
        //         AND now() <= spy_price_product_schedule.active_to
        //    ) A
        //    GROUP BY productID
        // LIMIT 1000
        // ) B
        // JOIN spy_price_product_schedule AS C ON (C.id_price_product_schedule = SUBSTRING(B.R from \'[0-9]+$\')::BIGINT) AND C.is_current = false;
        // ';
        //@todo finish work on converting sql to query
        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->find()
            ->getData();

        $aQuery = $this->getFactory()
            ->createPriceProductScheduleQuery()
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
                static::COL_PRODUCT_ID,
                sprintf(
                    'CONCAT(EXTRACT(now() - %s), \' \', EXTRACT(%s - now()), \' \', %s, \' \', %s, \' \', %s)',
                    SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM,
                    SpyPriceProductScheduleTableMap::COL_ACTIVE_TO,
                    SpyPriceProductScheduleTableMap::COL_NET_PRICE,
                    SpyPriceProductScheduleTableMap::COL_GROSS_PRICE,
                    SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE
                )
            )
            ->filterByActiveTo(['min' => new DateTime()], Criteria::GREATER_EQUAL)
            ->filterByActiveFrom(['max' => new DateTime()], Criteria::LESS_EQUAL)
            ->groupBy(static::COL_PRODUCT_ID);

        $bQuery = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($aQuery);

        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($bQuery)
            ->limit($this->getFactory()->getConfig()->getApplyBatchSize())
            ->find()
            ->getData();
    }
}
