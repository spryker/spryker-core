<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\Map\SpyPriceProductScheduleTableMap;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
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
        PropelConfig::DB_ENGINE_PGSQL => 'CAST(CONCAT(CONCAT(CAST(EXTRACT(epoch from now() - %s) + EXTRACT(epoch from %s - now()) AS INT), \'.\'), %s + %s) as DECIMAL)',
        PropelConfig::DB_ENGINE_MYSQL => 'CONCAT(CONCAT(CAST(TIMESTAMPDIFF(minute, %s, now()) + TIMESTAMPDIFF(minute, now(), %s) AS BINARY), \'.\'), %s + %s) + 0',
    ];

    protected const EXPRESSION_CONCATENATED_PRODUCT_ID = 'CONCAT(%s, \' \', %s, \' \', COALESCE(%s, 0), \'_\', COALESCE(%s, 0))';

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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisableByIdProductAbstract(int $idProductAbstract): array
    {
        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(true)
            ->filterByFkProductAbstract($idProductAbstract)
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
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisableByIdProductConcrete(int $idProductConcrete): array
    {
        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->joinWithCurrency()
            ->joinWithPriceType()
            ->leftJoinWithProduct()
            ->leftJoinWithProductAbstract()
            ->filterByIsCurrent(true)
            ->filterByFkProduct($idProductConcrete)
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
        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
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
        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
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
        $priceProductScheduleEntities = $this->getFactory()
            ->createPriceProductScheduleQuery()
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
        $query = $this->getFactory()
            ->createPriceProductScheduleQuery()
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

        return $this->getFactory()
            ->createPriceProductScheduleQuery()
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
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListById(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): ?PriceProductScheduleListTransfer {
        $priceProductScheduleListEntity = $this->getFactory()
            ->createPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($priceProductScheduleListTransfer->getIdPriceProductScheduleList())
            ->findOne();

        if ($priceProductScheduleListEntity === null) {
            return null;
        }

        return $this->getFactory()->createPriceProductScheduleListMapper()
            ->mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
                $priceProductScheduleListEntity,
                new PriceProductScheduleListTransfer()
            );
    }

    /**
     * @param int $idPriceProductSchedule
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer|null
     */
    public function findPriceProductScheduleById(int $idPriceProductSchedule): ?PriceProductScheduleTransfer
    {
        $priceProductScheduleEntity = $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIdPriceProductSchedule($idPriceProductSchedule)
            ->findOne();

        if ($priceProductScheduleEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createPriceProductScheduleMapper()
            ->mapPriceProductScheduleEntityToPriceProductScheduleTransfer($priceProductScheduleEntity, new PriceProductScheduleTransfer());
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListByName(string $name): ?PriceProductScheduleListTransfer
    {
        $priceProductScheduleListEntity = $this->getFactory()
            ->createPriceProductScheduleListQuery()
            ->filterByName($name)
            ->findOne();

        if ($priceProductScheduleListEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createPriceProductScheduleListMapper()
            ->mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
                $priceProductScheduleListEntity,
                new PriceProductScheduleListTransfer()
            );
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

        $priceProductScheduleEntity = $this->getFactory()
            ->createPriceProductScheduleMapper()
            ->mapPriceProductScheduleTransferToPriceProductScheduleEntity($priceProductScheduleTransfer, new SpyPriceProductSchedule());

        $priceProductScheduleQuery = $this->getFactory()
            ->createPriceProductScheduleQuery()
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
