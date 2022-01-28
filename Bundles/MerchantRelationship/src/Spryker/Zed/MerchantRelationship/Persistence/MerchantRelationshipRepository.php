<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipPersistenceFactory getFactory()
 */
class MerchantRelationshipRepository extends AbstractRepository implements MerchantRelationshipRepositoryInterface
{
    /**
     * @var string
     */
    protected const COL_MAX_ID = 'MAX_ID';

    /**
     * @var int
     */
    protected const DEFAULT_PAGINATION_OFFSET = 0;

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function getMerchantRelationshipById(int $idMerchantRelationship): ?MerchantRelationshipTransfer
    {
        /** @var \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship|null $spyMerchantRelation */
        $spyMerchantRelation = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($idMerchantRelationship)
            ->findOne();

        if (!$spyMerchantRelation) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantRelationshipMapper()
            ->mapEntityToMerchantRelationshipTransfer($spyMerchantRelation, new MerchantRelationshipTransfer());
    }

    /**
     * @param string $merchantRelationshipKey
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipByKey(string $merchantRelationshipKey): ?MerchantRelationshipTransfer
    {
        $spyMerchantRelation = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByMerchantRelationshipKey($merchantRelationshipKey)
            ->findOne();

        if (!$spyMerchantRelation) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantRelationshipMapper()
            ->mapEntityToMerchantRelationshipTransfer($spyMerchantRelation, new MerchantRelationshipTransfer());
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return array<int>
     */
    public function getIdAssignedBusinessUnitsByMerchantRelationshipId(int $idMerchantRelationship): array
    {
        return $this->getFactory()
            ->createMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->select([SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT])
            ->find()
            ->toArray();
    }

    /**
     * @param string $candidate
     *
     * @return bool
     */
    public function hasKey(string $candidate): bool
    {
        return $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->filterByMerchantRelationshipKey($candidate)
            ->exists();
    }

    /**
     * @return int
     */
    public function getMaxMerchantRelationshipId(): int
    {
        /** @var int|null $id */
        $id = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->withColumn(
                sprintf('MAX(%s)', SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP),
                static::COL_MAX_ID,
            )
            ->select([
                static::COL_MAX_ID,
            ])
            ->findOne();

        return (int)$id;
    }

    /**
     * @module Merchant
     * @module CompanyBusinessUnit
     *
     * @param int $idCompanyBusinessUnit
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function getAssignedMerchantRelationshipsByIdCompanyBusinessUnit(int $idCompanyBusinessUnit): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship[] $merchantRelationshipEntities */
        $merchantRelationshipEntities = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->joinWithCompanyBusinessUnit()
            ->joinWithMerchant()
            ->useSpyMerchantRelationshipToCompanyBusinessUnitQuery()
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->find();

        if ($merchantRelationshipEntities->isEmpty()) {
            return [];
        }

        $merchantRelationshipCollection = [];

        foreach ($merchantRelationshipEntities as $merchantRelationshipEntity) {
            $merchantRelationshipCollection[] = $this->getFactory()
                ->createPropelMerchantRelationshipMapper()
                ->mapEntityToMerchantRelationshipTransfer($merchantRelationshipEntity, new MerchantRelationshipTransfer());
        }

        return $merchantRelationshipCollection;
    }

    /**
     * @module CompanyBusinessUnit
     * @module Merchant
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function getMerchantRelationshipCollection(
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCollectionTransfer {
        $merchantRelationshipQuery = $this->getFactory()
            ->createMerchantRelationshipQuery()
            ->leftJoinCompanyBusinessUnit()
            ->innerJoinWithMerchant();

        $offset = static::DEFAULT_PAGINATION_OFFSET;
        $limit = $this->getFactory()->getConfig()->getDefaultPaginationLimit();

        $merchantRelationshipQuery = $this->addFiltersToMerchantRelationshipQueryFromCriteria($merchantRelationshipQuery, $merchantRelationshipCriteriaTransfer);
        $merchantRelationshipQuery = $this->addSortingToMerchantRelationshipQueryFromCriteria($merchantRelationshipQuery, $merchantRelationshipCriteriaTransfer);
        $paginationTransfer = $merchantRelationshipCriteriaTransfer->getPagination();

        $total = $merchantRelationshipQuery->count();
        $page = $this->calculatePageNumber($limit, $offset);

        if ($paginationTransfer) {
            $offset = $paginationTransfer->getFirstIndex() ?? $offset;
            $limit = $paginationTransfer->getMaxPerPage() ?? $limit;
            $page = $paginationTransfer->getPage() ?? $this->calculatePageNumber($limit, $offset);
        }

        $propelModelPager = $merchantRelationshipQuery->paginate($page, $limit);
        $merchantRelationshipEntities = $propelModelPager->getResults();

        $merchantRelationshipCollectionTransfer = $this->getFactory()
            ->createPropelMerchantRelationshipMapper()
            ->mapMerchantRelationshipEntitiesToMerchantRelationshipCollectionTransfer(
                $merchantRelationshipEntities,
                new MerchantRelationshipCollectionTransfer(),
                $propelModelPager,
            );

        if ($offset > $total) {
            return (new MerchantRelationshipCollectionTransfer())
                ->setPagination($merchantRelationshipCollectionTransfer->getPagination());
        }

        return $merchantRelationshipCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery $merchantRelationshipQuery
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function addSortingToMerchantRelationshipQueryFromCriteria(
        SpyMerchantRelationshipQuery $merchantRelationshipQuery,
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): SpyMerchantRelationshipQuery {
        $sortCollection = $merchantRelationshipCriteriaTransfer->getSortCollection();
        if (!$sortCollection || $sortCollection->getSorts()->count() === 0) {
            return $merchantRelationshipQuery;
        }

        foreach ($sortCollection->getSorts() as $sort) {
            if (!$this->columnExists($merchantRelationshipQuery, $sort->getField())) {
                continue;
            }

            $merchantRelationshipQuery->orderBy(
                $sort->getField(),
                $sort->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $merchantRelationshipQuery;
    }

    /**
     * @module CompanyBusinessUnit
     *
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery $merchantRelationshipQuery
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function addFiltersToMerchantRelationshipQueryFromCriteria(
        SpyMerchantRelationshipQuery $merchantRelationshipQuery,
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): SpyMerchantRelationshipQuery {
        $merchantRelationshipConditions = $merchantRelationshipCriteriaTransfer->getMerchantRelationshipConditions();
        if (!$merchantRelationshipConditions) {
            return $merchantRelationshipQuery;
        }

        if ($merchantRelationshipConditions->getMerchantRelationshipIds() !== []) {
            $merchantRelationshipQuery->filterByIdMerchantRelationship_In($merchantRelationshipConditions->getMerchantRelationshipIds());
        }

        if ($merchantRelationshipConditions->getMerchantIds() !== []) {
            $merchantRelationshipQuery->filterByFkMerchant_In($merchantRelationshipConditions->getMerchantIds());
        }

        if ($merchantRelationshipConditions->getCompanyIds() !== []) {
            $merchantRelationshipQuery
                ->useCompanyBusinessUnitQuery()
                    ->filterByFkCompany_In($merchantRelationshipConditions->getCompanyIds())
                ->endUse();
        }

        return $merchantRelationshipQuery;
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery $merchantRelationshipQuery
     * @param string $column
     *
     * @return bool
     */
    protected function columnExists(SpyMerchantRelationshipQuery $merchantRelationshipQuery, string $column): bool
    {
        if ($merchantRelationshipQuery->getTableMap()->hasColumn($column)) {
            return true;
        }

        $companyBusinessUnitJoin = $merchantRelationshipQuery->getModelJoinByTableName(SpyCompanyBusinessUnitTableMap::TABLE_NAME);
        if ($companyBusinessUnitJoin && $companyBusinessUnitJoin->getTableMap()->hasColumn($column)) {
            return true;
        }

        $merchantJoin = $merchantRelationshipQuery->getModelJoinByTableName(SpyMerchantTableMap::TABLE_NAME);
        if ($merchantJoin && $merchantJoin->getTableMap()->hasColumn($column)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return int
     */
    protected function calculatePageNumber(int $limit, int $offset): int
    {
        return $limit ? (int)($offset / $limit + 1) : 1;
    }
}
