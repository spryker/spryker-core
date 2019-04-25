<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
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

        $this->applyFilters($queryCompanyUser, $criteriaFilterTransfer);
        $companyUsersCount = $queryCompanyUser->count();

        $collection = $this->buildQueryFromCriteria($queryCompanyUser, $criteriaFilterTransfer->getFilter());

        /** @var \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $companyUserCollection */
        $companyUserCollection = $this->getFilteredCollection($collection, $criteriaFilterTransfer->getFilter());

        $collectionTransfer = $this->getFactory()
            ->createCompanyUsersRestApiMapper()
            ->mapCompanyUserCollection($companyUserCollection);

        $collectionTransfer->setFilter($criteriaFilterTransfer->getFilter());
        $collectionTransfer->setTotal($companyUsersCount);

        return $collectionTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery $queryCompanyUser
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return void
     */
    protected function applyFilters(SpyCompanyUserQuery $queryCompanyUser, CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer): void
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
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getFilteredCollection(ModelCriteria $query, ?FilterTransfer $filterTransfer = null)
    {
        return $query->mergeWith((new PropelFilterCriteria($filterTransfer))->toCriteria())->find();
    }
}
