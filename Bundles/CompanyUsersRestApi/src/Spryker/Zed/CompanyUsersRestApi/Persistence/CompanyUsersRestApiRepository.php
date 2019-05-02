<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\Persistence\CompanyUsersRestApiPersistenceFactory getFactory()
 */
class CompanyUsersRestApiRepository extends AbstractRepository implements CompanyUsersRestApiRepositoryInterface
{
    /**
     * @module CompanyUser
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer): CompanyUserCollectionTransfer
    {
        $queryCompanyUser = $this->getFactory()
            ->getCompanyUserPropelQuery()
            ->joinWithCustomer()
                ->useCustomerQuery()
            ->filterByAnonymizedAt(null, Criteria::ISNULL)
            ->endUse();

        $queryCompanyUser = $this->applyFilters($queryCompanyUser, $companyUserCriteriaFilterTransfer);
        $companyUsersCount = $queryCompanyUser->count();

        $filterTransfer = $companyUserCriteriaFilterTransfer->getFilter();
        if ($filterTransfer->getLimit()) {
            $queryCompanyUser->setLimit($filterTransfer->getLimit());
        }

        if ($filterTransfer->getOffset()) {
            $queryCompanyUser->setOffset($filterTransfer->getOffset());
        }

        $companyUserCollectionTransfer = $this->getFactory()
            ->createCompanyUsersRestApiMapper()
            ->mapCompanyUserCollection($queryCompanyUser->find()->toArray());

        $companyUserCollectionTransfer->setFilter($companyUserCriteriaFilterTransfer->getFilter());
        $companyUserCollectionTransfer->setTotal($companyUsersCount);

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyFilters(SpyCompanyUserQuery $queryCompanyUser, CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer): SpyCompanyUserQuery
    {
        if ($companyUserCriteriaFilterTransfer->getIdCompany()) {
            $queryCompanyUser->filterByFkCompany($companyUserCriteriaFilterTransfer->getIdCompany());
        }

        if ($companyUserCriteriaFilterTransfer->getCompanyUserIds()) {
            $queryCompanyUser->filterByIdCompanyUser_In($companyUserCriteriaFilterTransfer->getCompanyUserIds());
        }

        if ($companyUserCriteriaFilterTransfer->getIsActive()) {
            $queryCompanyUser->filterByIsActive($companyUserCriteriaFilterTransfer->getIsActive());
        }

        if ($companyUserCriteriaFilterTransfer->getCompanyBusinessUnitUuids()) {
            $queryCompanyUser->filterByFkCompanyBusinessUnit_In($companyUserCriteriaFilterTransfer->getCompanyBusinessUnitUuids());
        }

        return $queryCompanyUser;
    }
}
