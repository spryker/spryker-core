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
use Spryker\Zed\Propel\PropelFilterCriteria;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\Persistence\CompanyUsersRestApiPersistenceFactory getFactory()
 */
class CompanyUsersRestApiRepository extends AbstractRepository implements CompanyUsersRestApiRepositoryInterface
{
    /**
     * @module CompanyUser
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): CompanyUserCollectionTransfer
    {
        $queryCompanyUser = $this->getFactory()
            ->getCompanyUserPropelQuery()
            ->joinWithCustomer()
                ->useCustomerQuery()
            ->filterByAnonymizedAt(null, Criteria::ISNULL)
            ->endUse();

        $queryCompanyUser = $this->applyFilters($queryCompanyUser, $criteriaFilterTransfer);
        $companyUsersCount = $queryCompanyUser->count();

        $queryCompanyUser = $this->buildQueryFromCriteria($queryCompanyUser, $criteriaFilterTransfer->getFilter());

        $filterTransfer = $criteriaFilterTransfer->getFilter();
        if ($filterTransfer) {
            $queryCompanyUser->mergeWith(
                (new PropelFilterCriteria($filterTransfer))->toCriteria()
            );
        }

        $companyUserCollectionTransfer = $this->getFactory()
            ->createCompanyUsersRestApiMapper()
            ->mapCompanyUserCollection($queryCompanyUser->find());

        $companyUserCollectionTransfer->setFilter($criteriaFilterTransfer->getFilter());
        $companyUserCollectionTransfer->setTotal($companyUsersCount);

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function applyFilters(SpyCompanyUserQuery $queryCompanyUser, CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): SpyCompanyUserQuery
    {
        if ($criteriaFilterTransfer->getIdCompany()) {
            $queryCompanyUser->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getCompanyUserIds()) {
            $queryCompanyUser->filterByIdCompanyUser_In($criteriaFilterTransfer->getCompanyUserIds());
        }

        if ($criteriaFilterTransfer->getIsActive()) {
            $queryCompanyUser->filterByIsActive($criteriaFilterTransfer->getIsActive());
        }

        if ($criteriaFilterTransfer->getCompanyBusinessUnitUuids()) {
            $queryCompanyUser->filterByFkCompanyBusinessUnit_In($criteriaFilterTransfer->getCompanyBusinessUnitUuids());
        }

        return $queryCompanyUser;
    }
}
