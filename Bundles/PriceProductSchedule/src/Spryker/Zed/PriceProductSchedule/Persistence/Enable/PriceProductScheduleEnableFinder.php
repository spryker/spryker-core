<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Enable;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Exception\NotSupportedDbEngineException;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactoryInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;
use Spryker\Zed\Propel\PropelConfig;

class PriceProductScheduleEnableFinder implements PriceProductScheduleEnableFinderInterface
{
    /**
     * @var string
     */
    protected const COL_PRIORITY_TIME = 'prio_time';

    /**
     * @var string
     */
    protected const COL_PRIORITY_PRICE = 'prio_price';

    /**
     * @var string
     */
    protected const MESSAGE_NOT_SUPPORTED_DB_ENGINE = 'DB engine "%s" is not supported. Please extend EXPRESSION_CONCATENATED_RESULT_MAP';

    /**
     * @var array<string, string>
     */
    protected const EXPRESSION_TIMESTAMP_DIFF_MAP = [
        PropelConfig::DB_ENGINE_PGSQL => 'EXTRACT(EPOCH FROM (%s - now()))::INTEGER',
        PropelConfig::DB_ENGINE_MYSQL => 'timestampdiff(second, now(), %s)',
    ];

    /**
     * @var string
     */
    protected const EXPRESSION_WITH_NULL_CHECK = 'COALESCE(%s, 0) + COALESCE(%s, 0)';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface
     */
    protected PriceProductScheduleToPropelFacadeInterface $propelFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactoryInterface
     */
    protected PriceProductSchedulePersistenceFactoryInterface $priceProductSchedulePersistenceFactory;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected PriceProductScheduleConfig $priceProductScheduleConfig;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface
     */
    protected PriceProductScheduleMapperInterface $priceProductScheduleMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface $propelFacade
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactoryInterface $priceProductSchedulePersistenceFactory
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $priceProductScheduleConfig
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface $priceProductScheduleMapper
     */
    public function __construct(
        PriceProductScheduleToPropelFacadeInterface $propelFacade,
        PriceProductSchedulePersistenceFactoryInterface $priceProductSchedulePersistenceFactory,
        PriceProductScheduleConfig $priceProductScheduleConfig,
        PriceProductScheduleMapperInterface $priceProductScheduleMapper
    ) {
        $this->propelFacade = $propelFacade;
        $this->priceProductSchedulePersistenceFactory = $priceProductSchedulePersistenceFactory;
        $this->priceProductScheduleConfig = $priceProductScheduleConfig;
        $this->priceProductScheduleMapper = $priceProductScheduleMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToEnableByStore(StoreTransfer $storeTransfer): array
    {
        $priceProductScheduleQuery = $this->priceProductSchedulePersistenceFactory->createPriceProductScheduleQuery();
        $priceProductScheduleQuery->filterByFkStore($storeTransfer->getIdStore());

        $priceProductScheduleIds = $this->getPriceProductScheduleIds($priceProductScheduleQuery);

        return $this->findPriceProductSchedulesToEnableByPriceProductScheduleIds($priceProductScheduleIds);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToEnableByStoreAndIdProductAbstract(
        StoreTransfer $storeTransfer,
        int $idProductAbstract
    ): array {
        $priceProductScheduleQuery = $this->priceProductSchedulePersistenceFactory->createPriceProductScheduleQuery();
        $priceProductScheduleQuery->filterByFkStore($storeTransfer->getIdStore());
        $priceProductScheduleQuery->filterByFkProductAbstract($idProductAbstract);

        $priceProductScheduleIds = $this->getPriceProductScheduleIds($priceProductScheduleQuery);

        return $this->findPriceProductSchedulesToEnableByPriceProductScheduleIds($priceProductScheduleIds);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $idProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    public function findPriceProductSchedulesToEnableByStoreAndIdProductConcrete(
        StoreTransfer $storeTransfer,
        int $idProductConcrete
    ): array {
        $priceProductScheduleQuery = $this->priceProductSchedulePersistenceFactory->createPriceProductScheduleQuery();
        $priceProductScheduleQuery->filterByFkStore($storeTransfer->getIdStore());
        $priceProductScheduleQuery->filterByFkProduct($idProductConcrete);

        $priceProductScheduleIds = $this->getPriceProductScheduleIds($priceProductScheduleQuery);

        return $this->findPriceProductSchedulesToEnableByPriceProductScheduleIds($priceProductScheduleIds);
    }

    /**
     * @param list<int> $priceProductScheduleIds
     *
     * @return list<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    protected function findPriceProductSchedulesToEnableByPriceProductScheduleIds(array $priceProductScheduleIds): array
    {
        $priceProductScheduleEntities = $this->priceProductSchedulePersistenceFactory->createPriceProductScheduleQuery()
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIdPriceProductSchedule_In($priceProductScheduleIds)
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     *
     * @return list<int>
     */
    protected function getPriceProductScheduleIds(SpyPriceProductScheduleQuery $priceProductScheduleQuery): array
    {
        $timestampDiffExpression = sprintf(
            $this->createTimestampDifferenceExpression(),
            SpyPriceProductScheduleTableMap::COL_ACTIVE_TO,
        );

        $priceDiffExpression = sprintf(
            static::EXPRESSION_WITH_NULL_CHECK,
            SpyPriceProductScheduleTableMap::COL_NET_PRICE,
            SpyPriceProductScheduleTableMap::COL_GROSS_PRICE,
        );

        $priceProductSchedulesWithWeights = $priceProductScheduleQuery
            ->addAsColumn(static::COL_PRIORITY_TIME, $timestampDiffExpression)
            ->addAsColumn(static::COL_PRIORITY_PRICE, $priceDiffExpression)
            ->select([
                static::COL_PRIORITY_TIME,
                static::COL_PRIORITY_PRICE,
                SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE,
                SpyPriceProductScheduleTableMap::COL_FK_PRICE_TYPE,
                SpyPriceProductScheduleTableMap::COL_FK_PRODUCT,
                SpyPriceProductScheduleTableMap::COL_FK_CURRENCY,
                SpyPriceProductScheduleTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyPriceProductScheduleTableMap::COL_IS_CURRENT,
            ])
            ->joinWithPriceProductScheduleList()
            ->useInPriceProductScheduleListQuery()
                ->filterByIsActive(true)
            ->endUse()
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM))
            ->where(sprintf('%s >= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO))
            ->limit($this->priceProductScheduleConfig->getApplyBatchSize())
            ->find();

        return $this->getPriceProductScheduleIdsFilteredByWeight($priceProductSchedulesWithWeights);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $priceProductSchedulesWithWeights
     *
     * @return array<int>
     */
    protected function getPriceProductScheduleIdsFilteredByWeight(Collection $priceProductSchedulesWithWeights): array
    {
        $priceProductSchedulesIndexedByProductKey = [];
        foreach ($priceProductSchedulesWithWeights as $priceProductSchedule) {
            $uniqueProductKey = $this->createUniqueProductKey($priceProductSchedule);
            if (!isset($priceProductSchedulesIndexedByProductKey[$uniqueProductKey])) {
                $priceProductSchedulesIndexedByProductKey[$uniqueProductKey] = $priceProductSchedule;

                continue;
            }

            if (
                $priceProductSchedule[static::COL_PRIORITY_TIME] === $priceProductSchedulesIndexedByProductKey[$uniqueProductKey][static::COL_PRIORITY_TIME]
                && $priceProductSchedule[static::COL_PRIORITY_PRICE] < $priceProductSchedulesIndexedByProductKey[$uniqueProductKey][static::COL_PRIORITY_PRICE]
            ) {
                $priceProductSchedulesIndexedByProductKey[$uniqueProductKey] = $priceProductSchedule;

                continue;
            }

            if ($priceProductSchedule[static::COL_PRIORITY_TIME] < $priceProductSchedulesIndexedByProductKey[$uniqueProductKey][static::COL_PRIORITY_TIME]) {
                $priceProductSchedulesIndexedByProductKey[$uniqueProductKey] = $priceProductSchedule;
            }
        }

        return $this->getPriceProductScheduleIdsFilteredByEnabledSchedules($priceProductSchedulesIndexedByProductKey);
    }

    /**
     * @return string
     */
    protected function createTimestampDifferenceExpression(): string
    {
        $currentDatabaseEngineName = $this->propelFacade->getCurrentDatabaseEngine();

        return $this->getTimestampDiffExpressionByDbEngineName($currentDatabaseEngineName);
    }

    /**
     * @param string $databaseEngineName
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Persistence\Exception\NotSupportedDbEngineException
     *
     * @return string
     */
    protected function getTimestampDiffExpressionByDbEngineName(string $databaseEngineName): string
    {
        if (!isset(static::EXPRESSION_TIMESTAMP_DIFF_MAP[$databaseEngineName])) {
            throw new NotSupportedDbEngineException(
                sprintf(static::MESSAGE_NOT_SUPPORTED_DB_ENGINE, $databaseEngineName),
            );
        }

        return static::EXPRESSION_TIMESTAMP_DIFF_MAP[$databaseEngineName];
    }

    /**
     * @param array<string, int|null> $pricesWithMinimal
     *
     * @return string
     */
    protected function createUniqueProductKey(array $pricesWithMinimal): string
    {
        return sprintf(
            '%s-%s-%s-%s',
            $pricesWithMinimal[SpyPriceProductScheduleTableMap::COL_FK_PRICE_TYPE] ?? 0,
            $pricesWithMinimal[SpyPriceProductScheduleTableMap::COL_FK_CURRENCY],
            $pricesWithMinimal[SpyPriceProductScheduleTableMap::COL_FK_PRODUCT] ?? 0,
            $pricesWithMinimal[SpyPriceProductScheduleTableMap::COL_FK_PRODUCT_ABSTRACT],
        );
    }

    /**
     * @param array<string, array> $priceProductSchedulesIndexedByProductKey
     *
     * @return list<int>
     */
    protected function getPriceProductScheduleIdsFilteredByEnabledSchedules(
        array $priceProductSchedulesIndexedByProductKey
    ): array {
        $priceProductScheduleIdsFilteredByEnabledSchedules = [];
        foreach ($priceProductSchedulesIndexedByProductKey as $priceProductSchedule) {
            if ($priceProductSchedule[SpyPriceProductScheduleTableMap::COL_IS_CURRENT] === 1) {
                continue;
            }

            $priceProductScheduleIdsFilteredByEnabledSchedules[] =
                $priceProductSchedule[SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE];
        }

        return $priceProductScheduleIdsFilteredByEnabledSchedules;
    }
}
