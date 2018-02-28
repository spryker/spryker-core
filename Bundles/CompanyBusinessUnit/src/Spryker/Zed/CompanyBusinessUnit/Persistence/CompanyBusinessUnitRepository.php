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
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory getFactory()
 */
class CompanyBusinessUnitRepository extends AbstractRepository implements CompanyBusinessUnitRepositoryInterface
{
    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        int $idCompanyBusinessUnit
    ): CompanyBusinessUnitTransfer {
        $query = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit);
        $entitytransfer = $this->buildQueryFromCriteria($query)->findOne();

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer($entitytransfer, new CompanyBusinessUnitTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function filterCompanyBusinessUnits(
        CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        $companyBusinessUnitCriteriaFilterTransfer->requireIdCompany();
        $query = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByFkCompany($companyBusinessUnitCriteriaFilterTransfer->getIdCompany());

        if ($companyBusinessUnitCriteriaFilterTransfer->getIdCompanyUser() !== null) {
            $query->useCompanyUserQuery()
                ->filterByIdCompanyUser($companyBusinessUnitCriteriaFilterTransfer->getIdCompanyUser())
                ->endUse();
        }

        $collection = $this->buildQueryFromCriteria($query, $companyBusinessUnitCriteriaFilterTransfer->getFilter());
        $collection = $this->getPaginatedCollection($collection, $companyBusinessUnitCriteriaFilterTransfer->getPagination());

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

        $collectionTransfer->setPagination($companyBusinessUnitCriteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return bool
     */
    public function hasUsers(int $idCompanyBusinessUnit): bool
    {
        $query = $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit)
            ->joinWithCompanyUser();

        return ($this->buildQueryFromCriteria($query)->count() > 0);
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
