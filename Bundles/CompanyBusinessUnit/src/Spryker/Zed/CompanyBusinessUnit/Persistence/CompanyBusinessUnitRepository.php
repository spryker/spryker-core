<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory getFactory()
 */
class CompanyBusinessUnitRepository extends AbstractRepository implements CompanyBusinessUnitRepositoryInterface
{
    protected const TABLE_JOIN_PARENT_BUSINESS_UNIT = 'parentCompanyBusinessUnit';

    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_REFERENCE
     */
    protected const COL_CUSTOMER_REFERENCE = 'spy_customer.customer_reference';

    protected const COL_FK_CUSTOMER = 'fk_customer';

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        int $idCompanyBusinessUnit
    ): CompanyBusinessUnitTransfer {
        $query = $this->getSpyCompanyBusinessUnitQuery()
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
        $query = $this->getSpyCompanyBusinessUnitQuery();

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

        $this->filterCompanyBusinessUnitCollection($query, $criteriaFilterTransfer);

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
        $query = $this->getSpyCompanyBusinessUnitQuery()
            ->filterByFkCompany($idCompany);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entityTransfer, new CompanyBusinessUnitTransfer());
    }

    /**
     * @module CompanyUser
     * @module Customer
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->useCompanyUserQuery()
                ->joinCustomer()
            ->endUse()
            ->filterByIdCompanyBusinessUnit_In($companyBusinessUnitIds)
            ->select(static::COL_CUSTOMER_REFERENCE)
            ->find()
            ->toArray();
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    public function findCompanyBusinessUnitById(int $idCompanyBusinessUnit): ?CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitQuery = $this->getSpyCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit);

        $companyBusinessUnitEntity = $companyBusinessUnitQuery->findOne();

        if (!$companyBusinessUnitEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer($companyBusinessUnitEntity, new CompanyBusinessUnitTransfer());
    }

    /**
     * @param string $companyBusinessUnitUuid
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    public function findCompanyBusinessUnitByUuid(string $companyBusinessUnitUuid): ?CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitEntity = $this->getSpyCompanyBusinessUnitQuery()
            ->filterByUuid($companyBusinessUnitUuid)
            ->findOne();

        if (!$companyBusinessUnitEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
                $companyBusinessUnitEntity,
                new CompanyBusinessUnitTransfer()
            );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
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
        $this->mapPaginationModel($paginationTransfer, $paginationModel);

        return $paginationModel->getResults();
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function getSpyCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->leftJoinParentCompanyBusinessUnit(static::TABLE_JOIN_PARENT_BUSINESS_UNIT)
            ->with(static::TABLE_JOIN_PARENT_BUSINESS_UNIT)
            ->innerJoinWithCompany();
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Propel\Runtime\Util\PropelModelPager $paginationModel
     *
     * @return void
     */
    protected function mapPaginationModel(PaginationTransfer $paginationTransfer, PropelModelPager $paginationModel): void
    {
        $paginationTransfer
            ->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return void
     */
    protected function filterCompanyBusinessUnitCollection(
        SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery,
        CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilterTransfer
    ): void {
        if ($criteriaFilterTransfer->getCompanyBusinessUnitIds()) {
            $companyBusinessUnitQuery->filterByIdCompanyBusinessUnit_In($criteriaFilterTransfer->getCompanyBusinessUnitIds());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function hasCompanyUserByCustomer(CompanyUserTransfer $companyUserTransfer): bool
    {
        $companyUserTransfer
            ->requireFkCompanyBusinessUnit()
            ->requireFkCustomer();

        $companyUserQuery = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->useCompanyUserQuery();

        if (!$companyUserQuery->getTableMap()->hasColumn(static::COL_FK_CUSTOMER)) {
            return false;
        }

        if ($companyUserTransfer->getIdCompanyUser()) {
            $companyUserQuery
                ->filterByIdCompanyUser($companyUserTransfer->getIdCompanyUser(), Criteria::NOT_EQUAL);
        }

        return $companyUserQuery
            ->filterByFkCustomer($companyUserTransfer->getFkCustomer())
            ->endUse()
            ->filterByIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit())
            ->exists();
    }
}
