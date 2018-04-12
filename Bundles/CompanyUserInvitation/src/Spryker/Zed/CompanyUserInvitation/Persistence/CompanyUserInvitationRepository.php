<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationPersistenceFactory getFactory()
 */
class CompanyUserInvitationRepository extends AbstractRepository implements CompanyUserInvitationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getCompanyUserInvitationCollection(CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer): CompanyUserInvitationCollectionTransfer
    {
        $queryCompanyUserInvitation = $this->getFactory()
            ->createCompanyUserInvitationQuery()
            ->joinWithSpyCompanyBusinessUnit()
            ->joinWithSpyCompanyUser()
            ->innerJoinWithSpyCompanyUserInvitationStatus()
            ->useSpyCompanyUserInvitationStatusQuery()
                ->filterByStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_DELETED, Criteria::NOT_EQUAL)
            ->endUse();

        if ($criteriaFilterTransfer->getIdCompanyUserInvitation() !== null) {
            $queryCompanyUserInvitation->filterByIdCompanyUserInvitation($criteriaFilterTransfer->getIdCompanyUserInvitation());
        }

        if ($criteriaFilterTransfer->getFkCompanyUser() !== null) {
            $queryCompanyUserInvitation->filterByFkCompanyUser($criteriaFilterTransfer->getFkCompanyUser());
        }

        if ($criteriaFilterTransfer->getHash() !== null) {
            $queryCompanyUserInvitation->filterByHash($criteriaFilterTransfer->getHash());
        }

        if ($criteriaFilterTransfer->getFkCompanyUserInvitationStatus() !== null) {
            $queryCompanyUserInvitation
                ->useSpyCompanyUserInvitationStatusQuery()
                    ->filterByStatusKey($criteriaFilterTransfer->getFkCompanyUserInvitationStatus())
                ->endUse();
        }

        $companyUserInvitationCollection = $this->buildQueryFromCriteria($queryCompanyUserInvitation, $criteriaFilterTransfer->getFilter());
        $companyUserInvitationCollection = $this->getPaginatedCollection($companyUserInvitationCollection, $criteriaFilterTransfer->getPagination());

        $companyUserInvitationCollectionTransfer = $this->getFactory()
            ->createCompanyUserInvitationMapper()
            ->mapCompanyUserInvitationCollection($companyUserInvitationCollection);

        $companyUserInvitationCollectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $companyUserInvitationCollectionTransfer;
    }

    /**
     * @param string $statusKey
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer|null
     */
    public function findCompanyUserInvitationStatusByStatusKey(string $statusKey): ?CompanyUserInvitationStatusTransfer
    {
        $queryCompanyUserInvitationStatus = $this->getFactory()
            ->createCompanyUserInvitationStatusQuery()
            ->filterByStatusKey($statusKey);

        $entityTransfer = $this->buildQueryFromCriteria($queryCompanyUserInvitationStatus)->findOne();

        if ($entityTransfer !== null) {
            return $this->getFactory()
                ->createCompanyUserInvitationStatusMapper()
                ->mapEntityTransferToCompanyUserInvitationStatusTransfer($entityTransfer);
        }

        return null;
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
