<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory getFactory()
 */
class CompanyUserRepository extends AbstractRepository implements CompanyUserRepositoryInterface
{
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
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function filterCompanyUsers(CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer): CompanyUserCollectionTransfer
    {
        $queryCompanyUser = $this->getFactory()->createCompanyUserQuery();

        if ($companyUserCriteriaFilterTransfer->getIdCompany() !== null) {
            $queryCompanyUser->filterByFkCompany($companyUserCriteriaFilterTransfer->getIdCompany());
        }

        $collection = $this->buildQueryFromCriteria($queryCompanyUser, $companyUserCriteriaFilterTransfer->getFilter());
        $collection = $this->getPaginatedCollection($collection, $companyUserCriteriaFilterTransfer->getPagination());
        $this->populateCollectionWithRelation($collection, 'SpyCustomer');

        $collectionTransfer = $this->getFactory()
            ->createCompanyUserMapper()
            ->mapCompanyUserCollection($collection);

        $collectionTransfer->setPagination($companyUserCriteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\Collection|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPaginatedCollection(ModelCriteria $query, PaginationTransfer $paginationTransfer = null)
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
}
