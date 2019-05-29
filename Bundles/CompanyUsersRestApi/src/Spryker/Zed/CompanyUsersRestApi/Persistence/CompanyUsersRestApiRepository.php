<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyCompanyUserEntityTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\Persistence\CompanyUsersRestApiPersistenceFactory getFactory()
 */
class CompanyUsersRestApiRepository extends AbstractRepository implements CompanyUsersRestApiRepositoryInterface
{
    /**
     * @module Customer
     * @module Company
     * @module CompanyUser
     * @module CompanyBusinessUnit
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer): CompanyUserCollectionTransfer
    {
        $queryCompanyUser = $this->getFactory()
            ->getCompanyUserPropelQuery()
            ->joinWithCompany()
            ->joinWithCompanyBusinessUnit()
            ->joinWithCustomer()
            ->useCustomerQuery()
                ->filterByAnonymizedAt(null, Criteria::ISNULL)
            ->endUse();

        $queryCompanyUser = $this->applyFilters($queryCompanyUser, $companyUserCriteriaFilterTransfer);
        $companyUsersCount = $queryCompanyUser->count();

        $filterTransfer = $companyUserCriteriaFilterTransfer->getFilter();
        $queryCompanyUser = $this->setQueryFilters($queryCompanyUser, $filterTransfer);

        $companyUserEntityCollection = $queryCompanyUser->find();

        $companyUserCollectionTransfer = $this->getFactory()
            ->createCompanyUsersRestApiMapper()
            ->mapCompanyUserEntitiesToCompanyUserCollectionTransfer($companyUserEntityCollection, new CompanyUserCollectionTransfer());

        $companyRoleEntityCollection = $this->getCompanyRoleEntityCollectionForCompanyUserIds(
            $companyUserEntityCollection->getColumnValues(SpyCompanyUserEntityTransfer::ID_COMPANY_USER)
        );

        $companyUserCollectionTransfer = $this->getFactory()
            ->createCompanyUsersRestApiMapper()
            ->mapCompanyRoleCollectionTransferToCompanyUserCollection($companyRoleEntityCollection, $companyUserCollectionTransfer);

        $companyUserCollectionTransfer->setFilter($filterTransfer);
        $companyUserCollectionTransfer->setTotal($companyUsersCount);

        return $companyUserCollectionTransfer;
    }

    /**
     * @module CompanyRole
     *
     * @param int[] $companyUserIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function getCompanyRoleEntityCollectionForCompanyUserIds(array $companyUserIds): ObjectCollection
    {
        $queryCompanyRole = $this->getFactory()->getCompanyRolePropelQuery()
            ->joinWithSpyCompanyRoleToCompanyUser()
                ->useSpyCompanyRoleToCompanyUserQuery()
            ->filterByFkCompanyUser_In($companyUserIds)
            ->endUse();

        return $queryCompanyRole->find();
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyFilters(
        SpyCompanyUserQuery $queryCompanyUser,
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): SpyCompanyUserQuery {
        $queryCompanyUser = $this->applyByFkCompany($queryCompanyUser, $companyUserCriteriaFilterTransfer);
        $queryCompanyUser = $this->applyByCompanyUserIdsIn($queryCompanyUser, $companyUserCriteriaFilterTransfer);
        $queryCompanyUser = $this->applyByIsActive($queryCompanyUser, $companyUserCriteriaFilterTransfer);
        $queryCompanyUser = $this->applyByCompanyBusinessUnitUuidsIn($queryCompanyUser, $companyUserCriteriaFilterTransfer);
        $queryCompanyUser = $this->applyByCompanyRolesUuidsIn($queryCompanyUser, $companyUserCriteriaFilterTransfer);

        return $queryCompanyUser;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyByFkCompany(
        SpyCompanyUserQuery $queryCompanyUser,
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): SpyCompanyUserQuery {
        if ($companyUserCriteriaFilterTransfer->getIdCompany()) {
            $queryCompanyUser->filterByFkCompany($companyUserCriteriaFilterTransfer->getIdCompany());
        }

        return $queryCompanyUser;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyByCompanyUserIdsIn(
        SpyCompanyUserQuery $queryCompanyUser,
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): SpyCompanyUserQuery {
        if ($companyUserCriteriaFilterTransfer->getCompanyUserIds()) {
            $queryCompanyUser->filterByIdCompanyUser_In($companyUserCriteriaFilterTransfer->getCompanyUserIds());
        }

        return $queryCompanyUser;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyByIsActive(
        SpyCompanyUserQuery $queryCompanyUser,
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): SpyCompanyUserQuery {
        if ($companyUserCriteriaFilterTransfer->getIsActive()) {
            $queryCompanyUser->filterByIsActive($companyUserCriteriaFilterTransfer->getIsActive());
        }

        return $queryCompanyUser;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyByCompanyBusinessUnitUuidsIn(
        SpyCompanyUserQuery $queryCompanyUser,
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): SpyCompanyUserQuery {
        if ($companyUserCriteriaFilterTransfer->getCompanyBusinessUnitUuids()) {
            $queryCompanyUser->useCompanyBusinessUnitQuery()
                ->filterByUuid_In($companyUserCriteriaFilterTransfer->getCompanyBusinessUnitUuids())
                ->endUse();
        }

        return $queryCompanyUser;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyByCompanyRolesUuidsIn(
        SpyCompanyUserQuery $queryCompanyUser,
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): SpyCompanyUserQuery {
        if ($companyUserCriteriaFilterTransfer->getCompanyRolesUuids()) {
            $queryCompanyUser
                ->useSpyCompanyRoleToCompanyUserQuery()
                    ->useCompanyRoleQuery()
                        ->filterByUuid_In($companyUserCriteriaFilterTransfer->getCompanyRolesUuids())
                    ->endUse()
                ->endUse();
        }

        return $queryCompanyUser;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function setQueryFilters(
        SpyCompanyUserQuery $queryCompanyUser,
        ?FilterTransfer $filterTransfer
    ): SpyCompanyUserQuery {
        if (!$filterTransfer) {
            return $queryCompanyUser;
        }

        if ($filterTransfer->getLimit()) {
            $queryCompanyUser->setLimit($filterTransfer->getLimit());
        }

        if ($filterTransfer->getOffset()) {
            $queryCompanyUser->setOffset($filterTransfer->getOffset());
        }

        return $queryCompanyUser;
    }
}
