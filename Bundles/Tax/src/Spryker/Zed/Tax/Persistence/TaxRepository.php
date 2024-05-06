<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Generated\Shared\Transfer\TaxSetCriteriaTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTaxTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Tax\Persistence\TaxPersistenceFactory getFactory()
 */
class TaxRepository extends AbstractRepository implements TaxRepositoryInterface
{
    /**
     * @var string
     */
    protected const FK_TAX_SET = 'fkTaxSet';

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isTaxSetNameUnique(string $name): bool
    {
        $query = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByName($name);

        return !$query->exists();
    }

    /**
     * @param string $name
     * @param int $idTaxSet
     *
     * @return bool
     */
    public function isTaxSetNameAndIdUnique(string $name, int $idTaxSet): bool
    {
        $query = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByName($name)
            ->filterByIdTaxSet($idTaxSet, Criteria::NOT_EQUAL);

        return !$query->exists();
    }

    /**
     * @param int $idTaxRate
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer|null
     */
    public function findTaxRate(int $idTaxRate): ?TaxRateTransfer
    {
        $taxRateEntity = $this->getFactory()->createTaxRateQuery()->findOneByIdTaxRate($idTaxRate);

        if ($taxRateEntity === null) {
            return $taxRateEntity;
        }

        return $this->getFactory()->createTaxRateMapper()->mapTaxRateEntityToTaxRateTransfer(
            $taxRateEntity,
            new TaxRateTransfer(),
        );
    }

    /**
     * @param int $idTaxSet
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSet(int $idTaxSet): ?TaxSetTransfer
    {
        $taxSetEntity = $this->getFactory()->createTaxSetQuery()->findOneByIdTaxSet($idTaxSet);

        if ($taxSetEntity === null) {
            return $taxSetEntity;
        }

        return $this->getFactory()->createTaxSetMapper()->mapTaxSetEntityToTaxSetTransfer(
            $taxSetEntity,
            new TaxSetTransfer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetCriteriaTransfer $taxSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSetCollection(TaxSetCriteriaTransfer $taxSetCriteriaTransfer): TaxSetCollectionTransfer
    {
        $taxSetCollectionTransfer = new TaxSetCollectionTransfer();
        $taxSetQuery = $this->getFactory()->createTaxSetQuery();
        $taxSetQuery = $this->applyTaxSetFilters($taxSetCriteriaTransfer, $taxSetQuery);

        $paginationTransfer = $taxSetCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $taxSetQuery = $this->applyTaxSetPagination($taxSetQuery, $paginationTransfer);
            $taxSetCollectionTransfer->setPagination($paginationTransfer);
        }

        $taxEntities = $this->expandTaxSetsWithTaxRates($taxSetQuery->find(), $taxSetCriteriaTransfer);

        return $this->getFactory()
            ->createTaxSetMapper()
            ->mapTaxSetEntitiesToTaxSetCollectionTransfer(
                $taxEntities,
                $taxSetCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetQuery $taxSetQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    protected function applyTaxSetPagination(
        SpyTaxSetQuery $taxSetQuery,
        PaginationTransfer $paginationTransfer
    ): SpyTaxSetQuery {
        $paginationTransfer->setNbResults($taxSetQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $taxSetQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $taxSetQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Tax\Persistence\SpyTaxSet> $taxSetEntities
     * @param \Generated\Shared\Transfer\TaxSetCriteriaTransfer $taxSetCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\Tax\Persistence\SpyTaxSet>
     */
    public function expandTaxSetsWithTaxRates(
        Collection $taxSetEntities,
        TaxSetCriteriaTransfer $taxSetCriteriaTransfer
    ): Collection {
        /** @var \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity */
        foreach ($taxSetEntities as $taxSetEntity) {
            $taxSetEntity->initSpyTaxRates();
        }

        if (!$taxSetCriteriaTransfer->getWithTaxRates()) {
            return $taxSetEntities;
        }

        $taxSetEntitiesIndexedByTaxSetIds = $this->indexTaxSetEntitiesByTaxSetIds($taxSetEntities);

        $taxRateEntities = $this->getFactory()
            ->createTaxRateQuery()
            ->leftJoinWithCountry()
            ->useSpyTaxSetTaxQuery()
                ->filterByFkTaxSet_In(array_keys($taxSetEntitiesIndexedByTaxSetIds))
                ->withColumn(SpyTaxSetTaxTableMap::COL_FK_TAX_SET, static::FK_TAX_SET)
            ->endUse()
            ->find();

        foreach ($taxRateEntities as $taxRateEntity) {
            $taxSetId = $taxRateEntity->getVirtualColumn(static::FK_TAX_SET);
            if (!isset($taxSetEntitiesIndexedByTaxSetIds[$taxSetId])) {
                continue;
            }

            $taxSetEntitiesIndexedByTaxSetIds[$taxSetId]->addSpyTaxRate($taxRateEntity);
        }

        return $taxSetEntities;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Tax\Persistence\SpyTaxSet> $taxSetEntities
     *
     * @return array<int, \Orm\Zed\Tax\Persistence\SpyTaxSet>
     */
    protected function indexTaxSetEntitiesByTaxSetIds(Collection $taxSetEntities): array
    {
        $taxSetEntitiesIndexedByTaxSetIds = [];
        foreach ($taxSetEntities as $taxSetEntity) {
            $taxSetEntitiesIndexedByTaxSetIds[$taxSetEntity->getIdTaxSet()] = $taxSetEntity;
        }

        return $taxSetEntitiesIndexedByTaxSetIds;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetCriteriaTransfer $taxSetCriteriaTransfer
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetQuery $taxSetQuery
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function applyTaxSetFilters(TaxSetCriteriaTransfer $taxSetCriteriaTransfer, SpyTaxSetQuery $taxSetQuery): SpyTaxSetQuery
    {
        if (!$taxSetCriteriaTransfer->getTaxSetConditions()) {
            return $taxSetQuery;
        }

        if ($taxSetCriteriaTransfer->getTaxSetConditions()->getNames()) {
            $taxSetQuery->filterByName_In($taxSetCriteriaTransfer->getTaxSetConditions()->getNames());
        }

        return $taxSetQuery;
    }
}
