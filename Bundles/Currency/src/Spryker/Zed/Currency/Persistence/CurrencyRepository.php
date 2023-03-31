<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyCollectionTransfer;
use Generated\Shared\Transfer\CurrencyConditionsTransfer;
use Generated\Shared\Transfer\CurrencyCriteriaTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyStoreTableMap;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Currency\Persistence\CurrencyPersistenceFactory getFactory()
 */
class CurrencyRepository extends AbstractRepository implements CurrencyRepositoryInterface
{
    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyMapper
     */
    protected $currencyMapper;

    public function __construct()
    {
        $this->currencyMapper = $this->getFactory()->createCurrencyMapper();
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function findCurrencyByIsoCode(string $isoCode): ?CurrencyTransfer
    {
        $currencyEntity = $this->getFactory()
            ->createCurrencyPropelQuery()
            ->filterByCode($isoCode)
            ->findOne();

        if ($currencyEntity === null) {
            return null;
        }

        return $this->currencyMapper->mapCurrencyEntityToCurrencyTransfer(
            $currencyEntity,
            new CurrencyTransfer(),
        );
    }

    /**
     * @param array<string> $isoCodes
     *
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    public function getCurrencyTransfersByIsoCodes(array $isoCodes): array
    {
        $currencyEntities = $this->getFactory()
            ->createCurrencyPropelQuery()
            ->filterByCode_In($isoCodes)
            ->find();

        if ($currencyEntities->count() === 0) {
            return [];
        }

        return $this->mapCurrencyEntitiesToCurrencyTransfers($currencyEntities);
    }

    /**
     * Result format:
     * [
     *     $idStore => ['EUR', 'USD', ...],
     *     ...
     * ]
     *
     * @phpstan-return array<int, array<int, string>>
     *
     * @param array<int> $storeIds
     *
     * @return array<int, array<string>>
     */
    public function getCurrencyCodesGroupedByIdStore(array $storeIds): array
    {
        $currencyQuery = $this->getFactory()
            ->createCurrencyPropelQuery();
        $currencyQuery->useCurrencyStoreQuery()
            ->filterByFkStore_In($storeIds)
            ->endUse();
        $currencyQuery->select([SpyCurrencyTableMap::COL_CODE, SpyCurrencyStoreTableMap::COL_FK_STORE]);

        $currencyCodesByStoreId = [];
        foreach ($currencyQuery->find()->toArray() as $currencyData) {
            /** @var int $fkStore */
            $fkStore = $currencyData[SpyCurrencyStoreTableMap::COL_FK_STORE];
            /** @var string $code */
            $code = $currencyData[SpyCurrencyTableMap::COL_CODE];

            $currencyCodesByStoreId[$fkStore][] = $code;
        }

        return $currencyCodesByStoreId;
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function findCurrencyById(int $id): ?CurrencyTransfer
    {
        $currencyEntity = $this->getFactory()
            ->createCurrencyPropelQuery()
            ->findPk($id);

        if ($currencyEntity === null) {
            return null;
        }

        return $this->currencyMapper->mapCurrencyEntityToCurrencyTransfer($currencyEntity, new CurrencyTransfer());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Currency\Persistence\SpyCurrency> $currencyEntities
     *
     * @return array<string, \Generated\Shared\Transfer\CurrencyTransfer>
     */
    protected function mapCurrencyEntitiesToCurrencyTransfers(ObjectCollection $currencyEntities): array
    {
        $currencyTransfers = [];
        foreach ($currencyEntities as $currencyEntity) {
            $currencyTransfers[$currencyEntity->getCode()] = $this->currencyMapper
                ->mapCurrencyEntityToCurrencyTransfer($currencyEntity, new CurrencyTransfer());
        }

        return $currencyTransfers;
    }

    /**
     * @param array<int> $storeIds
     *
     * @return array<int|string, mixed>
     */
    public function getStoreDefaultCurrencyCodes(array $storeIds): array
    {
        $currencyQuery = $this->getFactory()
            ->createCurrencyPropelQuery();
        $currencyQuery->useStoreDefaultQuery()
                ->filterByIdStore_In($storeIds)
            ->endUse();
        $currencyQuery->select([
            SpyStoreTableMap::COL_ID_STORE,
            SpyCurrencyTableMap::COL_CODE,
        ]);

        $indexedCurrencyCodes = [];
        foreach ($currencyQuery->find() as $data) {
            $indexedCurrencyCodes[$data[SpyStoreTableMap::COL_ID_STORE]] = $data[SpyCurrencyTableMap::COL_CODE];
        }

        return $indexedCurrencyCodes;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyCriteriaTransfer $currencyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyCollectionTransfer
     */
    public function getCurrencyCollection(CurrencyCriteriaTransfer $currencyCriteriaTransfer): CurrencyCollectionTransfer
    {
        $currencyCollectionTransfer = new CurrencyCollectionTransfer();
        $currencyEntities = $this->getCurrencyEntitityCollection($currencyCriteriaTransfer, $currencyCollectionTransfer);

        if ($currencyEntities->count() === 0) {
            return $currencyCollectionTransfer;
        }

        return $currencyCollectionTransfer->setCurrencies(
            new ArrayObject(
                $this->mapCurrencyEntitiesToCurrencyTransfers($currencyEntities),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyCriteriaTransfer $currencyCriteriaTransfer
     * @param \Generated\Shared\Transfer\CurrencyCollectionTransfer $currencyCollectionTransfer
     *
     * @return \Propel\Runtime\Collection\Collection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>|mixed
     */
    protected function getCurrencyEntitityCollection(
        CurrencyCriteriaTransfer $currencyCriteriaTransfer,
        CurrencyCollectionTransfer $currencyCollectionTransfer
    ): mixed {
        $currencyQuery = $this->getFactory()
            ->createCurrencyPropelQuery();

        $currencyConditionsTransfer = $currencyCriteriaTransfer->getCurrencyConditions();
        if ($currencyConditionsTransfer) {
            $currencyQuery = $this->applyCurrencyConditions($currencyQuery, $currencyConditionsTransfer);
        }

        $paginationTransfer = $currencyCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $currencyQuery = $this->preparePagination($currencyQuery, $paginationTransfer);
            $currencyCollectionTransfer->setPagination($paginationTransfer);
        }

        $sortTransfers = $currencyCriteriaTransfer->getSortCollection();
        if ($sortTransfers->count() !== 0) {
            $currencyQuery = $this->applySorting($currencyQuery, $sortTransfers);
        }

        return $currencyQuery->find();
    }

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrencyQuery $currencyQuery
     * @param \Generated\Shared\Transfer\CurrencyConditionsTransfer $currencyConditionsTransfer
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    protected function applyCurrencyConditions(SpyCurrencyQuery $currencyQuery, CurrencyConditionsTransfer $currencyConditionsTransfer): SpyCurrencyQuery
    {
        if ($currencyConditionsTransfer->getCodes()) {
            $currencyQuery->filterByCode_In($currencyConditionsTransfer->getCodes());
        }
        if ($currencyConditionsTransfer->getNames()) {
            $currencyQuery->filterByName_In($currencyConditionsTransfer->getNames());
        }

        return $currencyQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\Currency\Persistence\SpyCurrencyQuery $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function preparePagination(SpyCurrencyQuery|ModelCriteria $query, PaginationTransfer $paginationTransfer): ModelCriteria
    {
        if ($paginationTransfer->getOffset() || $paginationTransfer->getLimit()) {
            $query->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $query;
        }

        $paginationModel = $query->paginate(
            $paginationTransfer->getPageOrFail(),
            $paginationTransfer->getMaxPerPageOrFail(),
        );

        $paginationTransfer->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \ArrayObject|array<\Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(ModelCriteria $query, ArrayObject|array $sortTransfers): ModelCriteria
    {
        foreach ($sortTransfers as $sortTransfer) {
            $query->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscendingOrFail() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $query;
    }
}
