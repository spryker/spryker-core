<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory getFactory()
 */
class CompanyUserRepository extends AbstractRepository implements CompanyUserRepositoryInterface
{
    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_REFERENCE
     */
    protected const COL_CUSTOMER_REFERENCE = 'spy_customer.customer_reference';

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByFkCustomer($idCustomer);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer !== null) {
            return $this->getFactory()
                ->createCompanyUserMapper()
                ->mapEntityTransferToCompanyUserTransfer($entityTransfer);
        }

        return null;
    }

    /**
     * @uses \Orm\Zed\Company\Persistence\SpyCompanyQuery
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByIsActive(true)
            ->filterByFkCustomer($idCustomer)
            ->joinCompany()
            ->useCompanyQuery()
                ->filterByIsActive(true)
            ->endUse();

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer !== null) {
            return $this->getFactory()
                ->createCompanyUserMapper()
                ->mapEntityTransferToCompanyUserTransfer($entityTransfer);
        }

        return null;
    }

    /**
     * @module Customer
     * @module Company
     *
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getActiveCompanyUsersByCustomerReference(string $customerReference): CompanyUserCollectionTransfer
    {
        $queryCompanyUser = $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByIsActive(true)
            ->useCustomerQuery()
                ->filterByCustomerReference($customerReference)
            ->endUse()
            ->joinWithCustomer()
            ->useCompanyQuery()
                ->filterByIsActive(true)
            ->endUse()
            ->joinWithCompany();

        $collection = $this->buildQueryFromCriteria($queryCompanyUser)->find();

        return $this->getFactory()
            ->createCompanyUserMapper()
            ->mapCompanyUserCollection($collection);
    }

    /**
     * @module Customer
     *
     * @uses \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): CompanyUserCollectionTransfer
    {
        $queryCompanyUser = $this->getFactory()
            ->createCompanyUserQuery()
            ->joinWithCustomer()
            ->useCustomerQuery()
                ->filterByAnonymizedAt(null, Criteria::ISNULL)
            ->endUse();

        $this->applyFilters($queryCompanyUser, $criteriaFilterTransfer);

        $collection = $this->buildQueryFromCriteria($queryCompanyUser, $criteriaFilterTransfer->getFilter());
        /** @var \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $companyUserCollection */
        $companyUserCollection = $this->getPaginatedCollection($collection, $criteriaFilterTransfer->getPagination());

        $collectionTransfer = $this->getFactory()
            ->createCompanyUserMapper()
            ->mapCompanyUserCollection($companyUserCollection);

        $collectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @uses \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     * @uses \Orm\Zed\Company\Persistence\SpyCompanyQuery
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer
    {
        // TODO: leftJoinWithCompany() for BC reasons, it will be innerJoin
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->joinWithCustomer()
            ->leftJoinWithCompany()
            ->filterByIdCompanyUser($idCompanyUser);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyUserMapper()
            ->mapEntityTransferToCompanyUserTransfer($entityTransfer);
    }

    /**
     * @module Customer
     *
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array
    {
        return $this->getFactory()
            ->createCompanyUserQuery()
            ->joinCustomer()
            ->filterByIdCompanyUser_In($companyUserIds)
            ->select(static::COL_CUSTOMER_REFERENCE)
            ->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByIdCompanyUser(CompanyUserTransfer $companyUserTransfer): ?CompanyUserTransfer
    {
        $companyUserEntityTransfer = $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByIdCompanyUser(
                $companyUserTransfer->getIdCompanyUser()
            )->findOne();

        if ($companyUserEntityTransfer !== null) {
            return $this->getFactory()
                ->createCompanyUserMapper()
                ->mapCompanyUserEntityToCompanyUserTransfer($companyUserEntityTransfer);
        }

        return null;
    }

    /**
     * @module Customer
     * @module Company
     *
     * @param string $uuidCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByUuid(string $uuidCompanyUser): ?CompanyUserTransfer
    {
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->joinWithCustomer()
            ->useCompanyQuery()
                ->filterByStatus(SpyCompanyTableMap::COL_STATUS_APPROVED)
                ->filterByIsActive(true)
            ->endUse()
            ->filterByIsActive(true)
            ->filterByUuid($uuidCompanyUser);

        $companyUserEntityTransfer = $this->buildQueryFromCriteria($query)->findOne();
        if ($companyUserEntityTransfer !== null) {
            return $this->getFactory()
                ->createCompanyUserMapper()
                ->mapEntityTransferToCompanyUserTransfer($companyUserEntityTransfer);
        }

        return null;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
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

        return $query->find();
    }

    /**
     * @module Customer
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findInitialCompanyUserByCompanyId(int $idCompany): ?CompanyUserTransfer
    {
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->joinWithCustomer()
            ->filterByFkCompany($idCompany)
            ->orderBy(SpyCompanyUserTableMap::COL_ID_COMPANY_USER, Criteria::ASC);
        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if (!$entityTransfer) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyUserMapper()
            ->mapEntityTransferToCompanyUserTransfer($entityTransfer);
    }

    /**
     * @module Company
     *
     * @param int $idCustomer
     *
     * @return int
     */
    public function countActiveCompanyUsersByIdCustomer(int $idCustomer): int
    {
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByFkCustomer($idCustomer)
            ->joinCompany()
            ->useCompanyQuery()
                ->filterByIsActive(true)
            ->endUse();

        return $query->count();
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return void
     */
    protected function applyFilters(SpyCompanyUserQuery $queryCompanyUser, CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): void
    {
        if ($criteriaFilterTransfer->getIdCompany() !== null) {
            $queryCompanyUser->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getCompanyUserIds()) {
            $queryCompanyUser->filterByIdCompanyUser_In($criteriaFilterTransfer->getCompanyUserIds());
        }

        if ($criteriaFilterTransfer->getIsActive() !== null) {
            $queryCompanyUser->filterByIsActive($criteriaFilterTransfer->getIsActive());
        }
    }

    /**
     * @module Customer
     * @module Company
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserById(int $idCompanyUser): ?CompanyUserTransfer
    {
        $companyUserEntity = $this->getFactory()
            ->createCompanyUserQuery()
            ->joinWithCustomer()
            ->leftJoinWithCompany()
            ->filterByIdCompanyUser($idCompanyUser)
            ->findOne();

        if (!$companyUserEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyUserMapper()
            ->mapCompanyUserEntityToCompanyUserTransfer($companyUserEntity);
    }

    /**
     * @module Customer
     * @module Company
     *
     * @param array $companyUserIds
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByIds(array $companyUserIds): array
    {
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByIdCompanyUser_In($companyUserIds)
            ->filterByIsActive(true)
            ->joinWithCompany()
            ->useCompanyQuery()
                ->filterByIsActive(true)
                ->filterByStatus(SpyCompanyTableMap::COL_STATUS_APPROVED)
            ->endUse()
            ->joinWithCustomer()
            ->useCustomerQuery()
                ->filterByAnonymizedAt(null, Criteria::ISNULL)
            ->endUse();

        $companyUserEntityCollection = $query->find();

        $companyUnitTransfers = [];
        $mapper = $this->getFactory()->createCompanyUserMapper();
        foreach ($companyUserEntityCollection as $companyUserEntity) {
            $companyUnitTransfers[] = $mapper->mapCompanyUserEntityToCompanyUserTransfer($companyUserEntity);
        }

        return $companyUnitTransfers;
    }

    /**
     * @module Customer
     *
     * @param array $companyIds
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCompanyIds(array $companyIds): array
    {
        $query = $this->getFactory()
            ->createCompanyUserQuery()
            ->filterByFkCompany_In($companyIds)
            ->filterByIsActive(true)
            ->useCustomerQuery()
                ->filterByAnonymizedAt(null, Criteria::ISNULL)
            ->endUse()
            ->select([SpyCompanyUserTableMap::COL_ID_COMPANY_USER]);

        return $query->find()->getData();
    }

    /**
     * @module Company
     * @module Customer
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer
    {
        $queryPattern = $companyUserCriteriaTransfer->getPattern() . '%';

        $companyUsersQuery = $this->getFactory()
            ->createCompanyUserQuery()
            ->joinWithCompany()
            ->joinWithCustomer()
            ->useCustomerQuery()
                ->filterByEmail_Like($queryPattern)
                ->_or()
                ->filterByLastName_Like($queryPattern)
                ->_or()
                ->filterByFirstName_Like($queryPattern)
                ->setIgnoreCase(true)
            ->endUse();

        if ($companyUserCriteriaTransfer->getLimit()) {
            $companyUsersQuery->limit($companyUserCriteriaTransfer->getLimit());
        }

        $companyUserEntityTransferCollection = $this->buildQueryFromCriteria($companyUsersQuery)->find();

        return $this->getFactory()
            ->createCompanyUserMapper()
            ->mapCompanyUserCollection($companyUserEntityTransferCollection);
    }
}
