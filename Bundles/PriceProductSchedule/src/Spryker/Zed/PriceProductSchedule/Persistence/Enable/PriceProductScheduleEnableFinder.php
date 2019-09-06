<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Enable;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\Exception\NotSupportedDbEngineException;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;
use Spryker\Zed\Propel\PropelConfig;

class PriceProductScheduleEnableFinder implements PriceProductScheduleEnableFinderInterface
{
    protected const COL_PRODUCT_ID = 'product_id';
    protected const COL_RESULT = 'result';

    protected const ALIAS_CONCATENATED = 'concatenated';
    protected const ALIAS_FILTERED = 'filtered';

    protected const MESSAGE_NOT_SUPPORTED_DB_ENGINE = 'DB engine "%s" is not supported. Please extend EXPRESSION_CONCATENATED_RESULT_MAP';

    protected const EXPRESSION_CONCATENATED_RESULT_MAP = [
        PropelConfig::DB_ENGINE_PGSQL => 'CAST(CONCAT(CONCAT(CAST(EXTRACT(epoch from now() - %s) + EXTRACT(epoch from %s - now()) AS INT), \'.\'), %s + %s) as DECIMAL)',
        PropelConfig::DB_ENGINE_MYSQL => 'CONCAT(CONCAT(CAST(TIMESTAMPDIFF(minute, %s, now()) + TIMESTAMPDIFF(minute, now(), %s) AS BINARY), \'.\'), %s + %s) + 0',
    ];

    protected const EXPRESSION_CONCATENATED_PRODUCT_ID = 'CONCAT(%s, \' \', %s, \' \', COALESCE(%s, 0), \'_\', COALESCE(%s, 0))';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $priceProductScheduleQuery;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface
     */
    protected $priceProductScheduleMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface $propelFacade
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $priceProductScheduleQuery
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $config
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface $priceProductScheduleMapper
     */
    public function __construct(
        PriceProductScheduleToPropelFacadeInterface $propelFacade,
        SpyPriceProductScheduleQuery $priceProductScheduleQuery,
        PriceProductScheduleConfig $config,
        PriceProductScheduleMapperInterface $priceProductScheduleMapper
    ) {
        $this->propelFacade = $propelFacade;
        $this->priceProductScheduleQuery = $priceProductScheduleQuery;
        $this->config = $config;
        $this->priceProductScheduleMapper = $priceProductScheduleMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStore(StoreTransfer $storeTransfer): array
    {
        $currentDatabaseEngineName = $this->propelFacade->getCurrentDatabaseEngine();
        $priceProductScheduleFilteredByMinResultSubQuery = $this->createPriceProductScheduleFilteredByMinResultSubQuery(
            $storeTransfer,
            $currentDatabaseEngineName
        );

        return $this->findPriceProductSchedulesToEnableByStoreResult(
            $priceProductScheduleFilteredByMinResultSubQuery,
            $storeTransfer,
            $currentDatabaseEngineName
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStoreAndIdProductAbstract(
        StoreTransfer $storeTransfer,
        int $idProductAbstract
    ): array {
        $currentDatabaseEngineName = $this->propelFacade->getCurrentDatabaseEngine();
        $priceProductScheduleFilteredByMinResultSubQuery = $this->createPriceProductScheduleFilteredByMinResultSubQuery(
            $storeTransfer,
            $currentDatabaseEngineName
        );

        return $this->findPriceProductSchedulesToEnableByStoreAndIdProductAbstractResult(
            $priceProductScheduleFilteredByMinResultSubQuery,
            $storeTransfer,
            $currentDatabaseEngineName,
            $idProductAbstract
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStoreAndIdProductConcrete(
        StoreTransfer $storeTransfer,
        int $idProductConcrete
    ): array {
        $currentDatabaseEngineName = $this->propelFacade->getCurrentDatabaseEngine();
        $priceProductScheduleFilteredByMinResultSubQuery = $this->createPriceProductScheduleFilteredByMinResultSubQuery(
            $storeTransfer,
            $currentDatabaseEngineName
        );

        return $this->findPriceProductSchedulesToEnableByStoreAndIdProductConcreteResult(
            $priceProductScheduleFilteredByMinResultSubQuery,
            $storeTransfer,
            $currentDatabaseEngineName,
            $idProductConcrete
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $currentDatabaseEngineName
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function createPriceProductScheduleFilteredByMinResultSubQuery(
        StoreTransfer $storeTransfer,
        string $currentDatabaseEngineName
    ): SpyPriceProductScheduleQuery {
        $priceProductScheduleConcatenatedSubQuery = $this->createPriceProductScheduleConcatenatedSubQuery(
            $storeTransfer,
            $currentDatabaseEngineName
        );

        return $this->priceProductScheduleQuery
            ->addSelectQuery($priceProductScheduleConcatenatedSubQuery, static::ALIAS_CONCATENATED, false)
            ->addAsColumn(static::COL_PRODUCT_ID, static::ALIAS_CONCATENATED . '.' . static::COL_PRODUCT_ID)
            ->addAsColumn(static::COL_RESULT, sprintf('min(%s)', static::ALIAS_CONCATENATED . '.' . static::COL_RESULT))
            ->groupBy(static::COL_PRODUCT_ID)
            ->limit($this->config->getApplyBatchSize());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $currentDatabaseEngineName
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function createPriceProductScheduleConcatenatedSubQuery(
        StoreTransfer $storeTransfer,
        string $currentDatabaseEngineName
    ): SpyPriceProductScheduleQuery {
        $concatenatedResultExpression = $this->getConcatenatedResultExpressionByDbEngineName($currentDatabaseEngineName);

        return $this->priceProductScheduleQuery
            ->select([static::COL_PRODUCT_ID])
            ->addAsColumn(
                static::COL_PRODUCT_ID,
                sprintf(
                    static::EXPRESSION_CONCATENATED_PRODUCT_ID,
                    SpyPriceProductScheduleTableMap::COL_FK_PRICE_TYPE,
                    SpyPriceProductScheduleTableMap::COL_FK_CURRENCY,
                    SpyPriceProductScheduleTableMap::COL_FK_PRODUCT,
                    SpyPriceProductScheduleTableMap::COL_FK_PRODUCT_ABSTRACT
                )
            )
            ->addAsColumn(
                static::COL_RESULT,
                sprintf(
                    $concatenatedResultExpression,
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
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM))
            ->where(sprintf('%s >= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO));
    }

    /**
     * @param string $databaseEngineName
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Persistence\Exception\NotSupportedDbEngineException
     *
     * @return string
     */
    protected function getConcatenatedResultExpressionByDbEngineName(string $databaseEngineName): string
    {
        if (isset(static::EXPRESSION_CONCATENATED_RESULT_MAP[$databaseEngineName]) === false) {
            throw new NotSupportedDbEngineException(
                sprintf(static::MESSAGE_NOT_SUPPORTED_DB_ENGINE, $databaseEngineName)
            );
        }

        return static::EXPRESSION_CONCATENATED_RESULT_MAP[$databaseEngineName];
    }

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $subQuery
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $dbEngineName
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    protected function findPriceProductSchedulesToEnableByStoreResult(SpyPriceProductScheduleQuery $subQuery, StoreTransfer $storeTransfer, string $dbEngineName): array
    {
        $priceProductScheduleEntities = $this->priceProductScheduleQuery
            ->addSelectQuery($subQuery, static::ALIAS_FILTERED, false)
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(false)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->where($this->getFilterByConcatenatedProductIdExpression())
            ->where($this->getFilterByConcatenatedResultExpression($dbEngineName))
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM))
            ->where(sprintf('%s >= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO))
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }

    /**
     * @return string
     */
    protected function getFilterByConcatenatedProductIdExpression(): string
    {
        return sprintf(
            '(%s) = %s',
            sprintf(
                static::EXPRESSION_CONCATENATED_PRODUCT_ID,
                SpyPriceProductScheduleTableMap::COL_FK_PRICE_TYPE,
                SpyPriceProductScheduleTableMap::COL_FK_CURRENCY,
                SpyPriceProductScheduleTableMap::COL_FK_PRODUCT,
                SpyPriceProductScheduleTableMap::COL_FK_PRODUCT_ABSTRACT
            ),
            static::ALIAS_FILTERED . '.' . static::COL_PRODUCT_ID
        );
    }

    /**
     * @param string $databaseEngineName
     *
     * @return string
     */
    protected function getFilterByConcatenatedResultExpression(string $databaseEngineName): string
    {
        $concatenatedResultExpression = $this->getConcatenatedResultExpressionByDbEngineName($databaseEngineName);

        return sprintf(
            '(%s) = %s',
            sprintf(
                $concatenatedResultExpression,
                SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM,
                SpyPriceProductScheduleTableMap::COL_ACTIVE_TO,
                SpyPriceProductScheduleTableMap::COL_NET_PRICE,
                SpyPriceProductScheduleTableMap::COL_GROSS_PRICE,
                SpyPriceProductScheduleTableMap::COL_ID_PRICE_PRODUCT_SCHEDULE
            ),
            static::ALIAS_FILTERED . '.' . static::COL_RESULT
        );
    }

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $subQuery
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $dbEngineName
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    protected function findPriceProductSchedulesToEnableByStoreAndIdProductAbstractResult(
        SpyPriceProductScheduleQuery $subQuery,
        StoreTransfer $storeTransfer,
        string $dbEngineName,
        int $idProductAbstract
    ): array {
        $priceProductScheduleEntities = $this->priceProductScheduleQuery
            ->addSelectQuery($subQuery, static::ALIAS_FILTERED, false)
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(false)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkProductAbstract($idProductAbstract)
            ->where($this->getFilterByConcatenatedProductIdExpression())
            ->where($this->getFilterByConcatenatedResultExpression($dbEngineName))
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM))
            ->where(sprintf('%s >= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO))
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
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery $subQuery
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $dbEngineName
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    protected function findPriceProductSchedulesToEnableByStoreAndIdProductConcreteResult(SpyPriceProductScheduleQuery $subQuery, StoreTransfer $storeTransfer, string $dbEngineName, int $idProductConcrete): array
    {
        $priceProductScheduleEntities = $this->priceProductScheduleQuery
            ->addSelectQuery($subQuery, static::ALIAS_FILTERED, false)
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(false)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkProduct($idProductConcrete)
            ->where($this->getFilterByConcatenatedProductIdExpression())
            ->where($this->getFilterByConcatenatedResultExpression($dbEngineName))
            ->where(sprintf('%s <= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_FROM))
            ->where(sprintf('%s >= now()', SpyPriceProductScheduleTableMap::COL_ACTIVE_TO))
            ->find()
            ->getData();

        return $this->priceProductScheduleMapper
            ->mapPriceProductScheduleEntitiesToPriceProductScheduleTransfers($priceProductScheduleEntities);
    }
}
