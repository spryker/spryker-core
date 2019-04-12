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
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactory getFactory()
 */
class PriceProductScheduleRepository extends AbstractRepository implements PriceProductScheduleRepositoryInterface
{
    protected const COL_PRODUCT_ID = 'product_id';
    protected const COL_RESULT = 'result';

    protected const ALIAS_CONCATENATED = 'concatenated';
    protected const ALIAS_FILTERED = 'filtered';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleMapperInterface
     */
    protected $priceProductScheduleMapper;

    public function __construct()
    {
        $this->priceProductScheduleMapper = $this->getFactory()->createPriceProductScheduleMapper();
    }

    /**
     * @param int $idPriceProductSchedule
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer|null
     */
    public function findByIdPriceProductSchedule(int $idPriceProductSchedule): ?PriceProductScheduleTransfer
    {
        $priceProductScheduleEntity = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIdPriceProductSchedule($idPriceProductSchedule)
            ->findOne();

        if ($priceProductScheduleEntity === null) {
            return null;
        }

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntityToPriceProductScheduleTransfer(
                $priceProductScheduleEntity,
                new PriceProductScheduleTransfer()
            );
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisable(): array
    {
        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIsCurrent(true)
            ->filterByActiveTo(['max' => new DateTime()], Criteria::LESS_EQUAL)
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findSimilarPriceProductSchedulesToDisable(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array {
        $priceProductScheduleEntities = $this->getFactory()
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

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStore(StoreTransfer $storeTransfer): array
    {
        $priceProductScheduleConcatenatedSubQuery = $this->createPriceProductScheduleConcatenatedSubQuery($storeTransfer);

        $priceProductScheduleFilteredByMinResultSubQuery = $this->createPriceProductScheduleFilteredByMinResultSubQuery($priceProductScheduleConcatenatedSubQuery);

        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($priceProductScheduleFilteredByMinResultSubQuery, static::ALIAS_FILTERED, false)
            ->filterByIsCurrent(false)
            ->where(SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE . ' = CAST(SUBSTRING(' . static::ALIAS_FILTERED . '.' . static::COL_RESULT . ' from \'[0-9]+$\') as BIGINT)')
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function createPriceProductScheduleConcatenatedSubQuery(
        StoreTransfer $storeTransfer
    ): SpyPriceProductScheduleQuery {
        return $this->getFactory()
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
            ->usePriceProductScheduleListQuery()
            ->filterByIsActive(true)
            ->endUse()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByActiveFrom(['max' => new DateTime()], Criteria::LESS_EQUAL)
            ->filterByActiveTo(['min' => new DateTime()], Criteria::GREATER_EQUAL);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleConcatenatedSubQuery
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function createPriceProductScheduleFilteredByMinResultSubQuery(
        SpyPriceProductScheduleQuery $priceProductScheduleConcatenatedSubQuery
    ): SpyPriceProductScheduleQuery {
        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($priceProductScheduleConcatenatedSubQuery, static::ALIAS_CONCATENATED, false)
            ->addAsColumn(static::COL_PRODUCT_ID, static::ALIAS_CONCATENATED . '.' . static::COL_PRODUCT_ID)
            ->addAsColumn(static::COL_RESULT, sprintf('min(%s)', static::ALIAS_CONCATENATED . '.' . static::COL_RESULT))
            ->groupBy(static::COL_PRODUCT_ID)
            ->limit($this->getFactory()->getConfig()->getApplyBatchSize());
    }
}
