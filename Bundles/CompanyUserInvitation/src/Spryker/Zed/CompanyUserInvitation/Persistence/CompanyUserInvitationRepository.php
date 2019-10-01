<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitationQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationPersistenceFactory getFactory()
 */
class CompanyUserInvitationRepository extends AbstractRepository implements CompanyUserInvitationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $companyUserInvitationCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function getCompanyUserInvitationCollection(
        CompanyUserInvitationCriteriaFilterTransfer $companyUserInvitationCriteriaFilterTransfer
    ): CompanyUserInvitationCollectionTransfer {
        $queryCompanyUserInvitation = $this->getFactory()
            ->createCompanyUserInvitationQuery()
            ->joinWithSpyCompanyBusinessUnit()
            ->joinWithSpyCompanyUserInvitationStatus();

        $queryCompanyUserInvitation = $this->applyQueryFilters($queryCompanyUserInvitation, $companyUserInvitationCriteriaFilterTransfer);

        if ($companyUserInvitationCriteriaFilterTransfer->getFilter() !== null) {
            $queryCompanyUserInvitation = $this->buildQueryFromCriteria(
                $queryCompanyUserInvitation,
                $companyUserInvitationCriteriaFilterTransfer->getFilter()
            );
        }

        if ($companyUserInvitationCriteriaFilterTransfer->getPagination() === null) {
            return $this->getFactory()
            ->createCompanyUserInvitationMapper()
            ->mapCompanyUserInvitationCollection($queryCompanyUserInvitation->find());
        }

        $pager = $queryCompanyUserInvitation->paginate(
            $companyUserInvitationCriteriaFilterTransfer->getPagination()->requirePage()->getPage(),
            $companyUserInvitationCriteriaFilterTransfer->getPagination()->requireMaxPerPage()->getMaxPerPage()
        );

        $companyUserInvitationCollectionTransfer = $this->getFactory()
            ->createCompanyUserInvitationMapper()
            ->mapCompanyUserInvitationCollection($pager->getResults());

        $companyUserInvitationCollectionTransfer->setPagination(
            $this->hydratePaginationTransfer($companyUserInvitationCriteriaFilterTransfer->getPagination(), $pager)
        );

        return $companyUserInvitationCollectionTransfer;
    }

    /**
     * @param string $statusKey
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer|null
     */
    public function findCompanyUserInvitationStatusByStatusKey(string $statusKey): ?CompanyUserInvitationStatusTransfer
    {
        $spyCompanyUserInvitation = $this->getFactory()
            ->createCompanyUserInvitationStatusQuery()
            ->filterByStatusKey($statusKey)
            ->findOne();

        if ($spyCompanyUserInvitation !== null) {
            $companyUserInvitationStatusTransfer = new CompanyUserInvitationStatusTransfer();
            $companyUserInvitationStatusTransfer->fromArray($spyCompanyUserInvitation->toArray(), true);

            return $companyUserInvitationStatusTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer|null
     */
    public function findCompanyUserInvitationById(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): ?CompanyUserInvitationTransfer {
        $spyCompanyUserInvitation = $this->getFactory()
            ->createCompanyUserInvitationQuery()
            ->filterByIdCompanyUserInvitation($companyUserInvitationTransfer->getIdCompanyUserInvitation())
            ->findOne();

        if ($spyCompanyUserInvitation !== null) {
            return $this->getFactory()
                ->createCompanyUserInvitationMapper()
                ->mapSpyCompanyUserInvitationToCompanyUserInvitationTransfer($spyCompanyUserInvitation);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function getCompanyUserInvitationByHash(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationTransfer {
        $spyCompanyUserInvitation = $this->getFactory()
            ->createCompanyUserInvitationQuery()
            ->joinWithSpyCompanyBusinessUnit()
            ->joinWithSpyCompanyUserInvitationStatus()
            ->filterByHash($companyUserInvitationTransfer->getHash())
            ->findOne();

        if ($spyCompanyUserInvitation == null) {
            return $companyUserInvitationTransfer;
        }

        return $this->getFactory()
            ->createCompanyUserInvitationMapper()
            ->mapSpyCompanyUserInvitationToCompanyUserInvitationTransfer($spyCompanyUserInvitation);
    }

    /**
     * @param \Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitationQuery $queryCompanyUserInvitation
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $companyUserInvitationCriteriaFilterTransfer
     *
     * @return \Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitationQuery
     */
    protected function applyQueryFilters(
        SpyCompanyUserInvitationQuery $queryCompanyUserInvitation,
        CompanyUserInvitationCriteriaFilterTransfer $companyUserInvitationCriteriaFilterTransfer
    ): SpyCompanyUserInvitationQuery {
        if ($companyUserInvitationCriteriaFilterTransfer->getFkCompany()) {
            $queryCompanyUserInvitation->useSpyCompanyUserQuery()->filterByFkCompany(
                $companyUserInvitationCriteriaFilterTransfer->getFkCompany(),
                Criteria::IN
            )->endUse();
        }

        if ($companyUserInvitationCriteriaFilterTransfer->getCompanyUserInvitationStatusKeyIn()) {
            $queryCompanyUserInvitation->useSpyCompanyUserInvitationStatusQuery()->filterByStatusKey(
                $companyUserInvitationCriteriaFilterTransfer->getCompanyUserInvitationStatusKeyIn(),
                Criteria::IN
            )->endUse();
        }

        if ($companyUserInvitationCriteriaFilterTransfer->getCompanyUserInvitationStatusKeyNotIn()) {
            $queryCompanyUserInvitation->useSpyCompanyUserInvitationStatusQuery()->filterByStatusKey(
                $companyUserInvitationCriteriaFilterTransfer->getCompanyUserInvitationStatusKeyNotIn(),
                Criteria::NOT_IN
            )->endUse();
        }

        return $queryCompanyUserInvitation;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQueryFromCriteria(ModelCriteria $criteria, ?FilterTransfer $filterTransfer = null): ModelCriteria
    {
        $criteria = parent::buildQueryFromCriteria($criteria, $filterTransfer);

        $criteria->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $criteria;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Propel\Runtime\Util\PropelModelPager $paginationModel
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function hydratePaginationTransfer(
        PaginationTransfer $paginationTransfer,
        PropelModelPager $paginationModel
    ): PaginationTransfer {
        return $paginationTransfer
            ->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());
    }
}
