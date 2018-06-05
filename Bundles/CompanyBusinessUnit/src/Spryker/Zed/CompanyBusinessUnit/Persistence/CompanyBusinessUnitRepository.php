<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\AbstractSpyCompanyBusinessUnitQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory getFactory()
 */
class CompanyBusinessUnitRepository extends AbstractRepository implements CompanyBusinessUnitRepositoryInterface
{
    protected const TABLE_JOIN_UNIT_PARENT = 'parentCompanyBusinessUnit';

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        int $idCompanyBusinessUnit
    ): CompanyBusinessUnitTransfer {
        $query = $this->getQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit);
        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entityTransfer, new CompanyBusinessUnitTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        $query = $this->getQuery();

        if ($criteriaFilterTransfer->getIdCompany()) {
            $query
                ->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getIdCompanyUser() !== null) {
            $query
                ->useCompanyUserQuery()
                    ->filterByIdCompanyUser($criteriaFilterTransfer->getIdCompanyUser())
                ->endUse();
        }

        $collection = $this->buildQueryFromCriteria($query, $criteriaFilterTransfer->getFilter());
        $collection = $this->getPaginatedCollection($collection, $criteriaFilterTransfer->getPagination());

        $collectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        foreach ($collection as $businessUnitEntity) {
            $businessUnitTransfer = $this->getFactory()
                ->createCompanyBusinessUnitMapper()
                ->mapEntityTransferToBusinessUnitTransfer(
                    $businessUnitEntity,
                    new CompanyBusinessUnitTransfer()
                );

            $collectionTransfer->addCompanyBusinessUnit($businessUnitTransfer);
        }

        $collectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return bool
     */
    public function hasUsers(int $idCompanyBusinessUnit): bool
    {
        $existsSpyCompanyBusinessUnit = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->useCompanyUserQuery()
                ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->endUse()
            ->exists();

        return $existsSpyCompanyBusinessUnit;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    public function findDefaultBusinessUnitByCompanyId(int $idCompany): ?CompanyBusinessUnitTransfer
    {
        $query = $this->getQuery()
            ->filterByFkCompany($idCompany);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entityTransfer, new CompanyBusinessUnitTransfer());
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer === null) {
            return $query->find();
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getResults();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\AbstractSpyCompanyBusinessUnitQuery
     */
    protected function getQuery(): AbstractSpyCompanyBusinessUnitQuery
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->leftJoinParentCompanyBusinessUnit(static::TABLE_JOIN_UNIT_PARENT)
            ->with(static::TABLE_JOIN_UNIT_PARENT)
            ->innerJoinWithCompany();
    }
}
