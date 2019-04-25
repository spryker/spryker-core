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
use Spryker\Zed\PriceProductSchedule\Persistence\Exception\NotSupportedDbEngineException;
use Spryker\Zed\Propel\PropelConfig;
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

    protected const MESSAGE_NOT_SUPPORTED_DB_ENGINE = 'DB engine "%s" is not supported. Please extend EXPRESSION_CONCATENATED_RESULT_MAP';

    protected const EXPRESSION_CONCATENATED_RESULT_MAP = [
        PropelConfig::DB_ENGINE_PGSQL => 'CONCAT(EXTRACT(epoch from now() - %s), \' \', EXTRACT(epoch from %s - now()), \' \', %s, \' \', %s, \' \', %s)',
        PropelConfig::DB_ENGINE_MYSQL => 'CONCAT(UNIX_TIMESTAMP(now() - %s), \' \', UNIX_TIMESTAMP(%s - now()), \' \', %s, \' \', %s, \' \', %s)',
    ];

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleMapperInterface
     */
    protected $priceProductScheduleMapper;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPropelFacadeInterface
     */
    protected $propelFacade;

    public function __construct()
    {
        $this->priceProductScheduleMapper = $this->getFactory()->createPriceProductScheduleMapper();
        $this->propelFacade = $this->getFactory()->getPropelFacade();
    }

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisable(): array
    {
        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(true)
            ->filterByActiveTo(['max' => new DateTime()], Criteria::LESS_EQUAL)
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
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findSimilarPriceProductSchedulesToDisable(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $priceProductTransfer
            ->requireMoneyValue()
            ->requirePriceType();

        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
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

    /**
     * @module Product
     * @module PriceProduct
     * @module Currency
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableByStore(StoreTransfer $storeTransfer): array
    {
        $priceProductScheduleFilteredByMinResultSubQuery = $this->createPriceProductScheduleFilteredByMinResultSubQuery($storeTransfer);

        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($priceProductScheduleFilteredByMinResultSubQuery, static::ALIAS_FILTERED, false)
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
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
        $currentDatabaseEngineName = $this->propelFacade->getCurrentDatabaseEngine();
        $concatenatedResultExpression = $this->getConcatenatedResultExpressionByDbEngineName($currentDatabaseEngineName);

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
            ->filterByActiveFrom(['max' => new DateTime()], Criteria::LESS_EQUAL)
            ->filterByActiveTo(['min' => new DateTime()], Criteria::GREATER_EQUAL);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function createPriceProductScheduleFilteredByMinResultSubQuery(
        StoreTransfer $storeTransfer
    ): SpyPriceProductScheduleQuery {
        $priceProductScheduleConcatenatedSubQuery = $this->createPriceProductScheduleConcatenatedSubQuery($storeTransfer);

        return $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->addSelectQuery($priceProductScheduleConcatenatedSubQuery, static::ALIAS_CONCATENATED, false)
            ->addAsColumn(static::COL_PRODUCT_ID, static::ALIAS_CONCATENATED . '.' . static::COL_PRODUCT_ID)
            ->addAsColumn(static::COL_RESULT, sprintf('min(%s)', static::ALIAS_CONCATENATED . '.' . static::COL_RESULT))
            ->groupBy(static::COL_PRODUCT_ID)
            ->limit($this->getFactory()->getConfig()->getApplyBatchSize());
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
}
