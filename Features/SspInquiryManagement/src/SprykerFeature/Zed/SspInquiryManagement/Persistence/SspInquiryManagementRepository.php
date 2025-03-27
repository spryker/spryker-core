<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementPersistenceFactory getFactory()
 */
class SspInquiryManagementRepository extends AbstractRepository implements SspInquiryManagementRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCollectionTransfer {
         $sspInquiryCollectionTransfer = new SspInquiryCollectionTransfer();

         $sspInquiryQuery = $this->getFactory()->createSspInquiryQuery()
            ->joinWithStateMachineItemState()
            ->withColumn(SpyStateMachineItemStateTableMap::COL_NAME, 'status');

         $sspInquiryQuery = $this->applyFilters($sspInquiryQuery, $sspInquiryCriteriaTransfer);

         $sspInquiryEntities = $this->getPaginatedCollection($sspInquiryQuery, $sspInquiryCriteriaTransfer->getPagination());

         $sspInquiryMapper = $this->getFactory()->createSspInquiryMapper();

        foreach ($sspInquiryEntities as $sspInquiryEntity) {
             $sspInquiryCollectionTransfer->addSspInquiry(
                 $sspInquiryMapper->mapSspInquiryEntityToSspInquiryTransfer($sspInquiryEntity, new SspInquiryTransfer()),
             );
        }

         $sspInquiryCollectionTransfer->setPagination($sspInquiryCriteriaTransfer->getPagination());

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryFileCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
         $sspInquiryFileQuery = $this->getFactory()->createSspInquiryFileQuery();

         $sspInquiryFileQuery->filterByFkSspInquiry_In($sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()->getSspInquiryIds());

         $sspInquiryCollectionTransfer = new SspInquiryCollectionTransfer();

         $sspInquiryFiles = [];
        foreach ($sspInquiryFileQuery->find() as $sspInquiryFileEntity) {
             $sspInquiryFiles[$sspInquiryFileEntity->getFkSspInquiry()][] = (new FileTransfer())
                ->setIdFile($sspInquiryFileEntity->getFkFile())
                ->setUuid($sspInquiryFileEntity->getUuid());
        }

        foreach ($sspInquiryFiles as $idSspInquiry => $files) {
             $sspInquiryCollectionTransfer->addSspInquiry(
                 (new SspInquiryTransfer())
                    ->setIdSspInquiry((int)$idSspInquiry)
                    ->setFiles(new ArrayObject($files)),
             );
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryOrderCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
         $sspInquiryOrderQuery = $this->getFactory()->createSspInquiryOrderQuery();

         $sspInquiryOrderQuery->filterByFkSspInquiry_In($sspInquiryCriteriaTransfer->getSspInquiryConditionsOrFail()->getSspInquiryIds());

         $sspInquiryCollectionTransfer = new SspInquiryCollectionTransfer();

        foreach ($sspInquiryOrderQuery->find() as $sspInquiryOrderEntity) {
             $sspInquiryCollectionTransfer->addSspInquiry(
                 (new SspInquiryTransfer())
                    ->setIdSspInquiry($sspInquiryOrderEntity->getFkSspInquiry())
                    ->setOrder((new OrderTransfer())->setIdSalesOrder($sspInquiryOrderEntity->getFkSalesOrder())),
             );
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param array<int> $stateIds
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array
    {
        /** @var \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery<\Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry> $sspInquiryQuery */
         $sspInquiryQuery = $this->getFactory()
            ->createSspInquiryQuery()
            ->joinWithStateMachineItemState()
            ->useStateMachineItemStateQuery()
            ->joinWithProcess()
            ->endUse();

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry> $sspInquiryEntities */
         $sspInquiryEntities = $sspInquiryQuery
            ->filterByFkStateMachineItemState_In($stateIds)
            ->find();

        return $this->getFactory()->createSspInquiryMapper()->mapSspInquiryEntityCollectionToStateMachineItemTransfers(
            $sspInquiryEntities,
        );
    }

    /**
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery $sspInquiryQuery
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery
     */
    protected function applyFilters(SpySspInquiryQuery $sspInquiryQuery, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SpySspInquiryQuery
    {
         $sspInquiryQuery = $this->applySorting($sspInquiryQuery, $sspInquiryCriteriaTransfer->getSortCollection());

         $sspInquiryConditions = $sspInquiryCriteriaTransfer->getSspInquiryConditions();

        if (!$sspInquiryConditions) {
            return $sspInquiryQuery;
        }

        if ($sspInquiryConditions->getSspInquiryIds() !== []) {
             $sspInquiryQuery->filterByIdSspInquiry_In($sspInquiryConditions->getSspInquiryIds());
        }

        if ($sspInquiryConditions->getReferences() !== []) {
             $sspInquiryQuery->filterByReference_In($sspInquiryConditions->getReferences());
        }

        if ($sspInquiryConditions->getType() !== null) {
             $sspInquiryQuery->filterByType($sspInquiryConditions->getType());
        }

        if ($sspInquiryConditions->getStatus() !== null) {
             $sspInquiryQuery
                ->useStateMachineItemStateQuery()
                    ->filterByName($sspInquiryConditions->getStatus())
                ->endUse();
        }

        $this->applySspInquiryOwnerFilter($sspInquiryQuery, $sspInquiryConditions);

        if ($sspInquiryConditions->getCreatedDateFrom() !== null) {
             $sspInquiryQuery->filterByCreatedAt($sspInquiryConditions->getCreatedDateFrom(), ModelCriteria::GREATER_EQUAL);
        }

        if ($sspInquiryConditions->getCreatedDateTo() !== null) {
             $sspInquiryQuery->filterByCreatedAt($sspInquiryConditions->getCreatedDateTo(), ModelCriteria::LESS_EQUAL);
        }

        if ($sspInquiryConditions->getFkStore() !== null) {
             $sspInquiryQuery->filterByFkStore($sspInquiryConditions->getFkStore());
        }

        return $sspInquiryQuery;
    }

    /**
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery $sspInquiryQuery
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditions
     *
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery
     */
    public function applySspInquiryOwnerFilter(SpySspInquiryQuery $sspInquiryQuery, SspInquiryConditionsTransfer $sspInquiryConditions): SpySspInquiryQuery
    {
         $sspInquiryOwnerConditionGroup = $sspInquiryConditions->getSspInquiryOwnerConditionGroup();

        if ($sspInquiryOwnerConditionGroup) {
            $hasOwnerCondition = false;
            $companyUserQuery = $sspInquiryQuery->useSpyCompanyUserQuery();

            if ($sspInquiryOwnerConditionGroup->getFkCompanyUser()) {
                $hasOwnerCondition = true;
                $companyUserQuery->filterByIdCompanyUser($sspInquiryOwnerConditionGroup->getFkCompanyUser());
            }

            if ($sspInquiryOwnerConditionGroup->getFkCompany()) {
                if ($hasOwnerCondition) {
                    $companyUserQuery->_or();
                }

                $hasOwnerCondition = true;
                $companyUserQuery->filterByFkCompany($sspInquiryOwnerConditionGroup->getFkCompany());
            }

            if ($sspInquiryOwnerConditionGroup->getFkCompanyBusinessUnit()) {
                if ($hasOwnerCondition) {
                    $companyUserQuery->_or();
                }

                $companyUserQuery->filterByFkCompanyBusinessUnit($sspInquiryOwnerConditionGroup->getFkCompanyBusinessUnitOrFail());
            }

            $companyUserQuery->endUse();
        }

        return $sspInquiryQuery;
    }

    /**
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery $sspInquiryQuery
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SortTransfer> $sortCollection
     *
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery
     */
    protected function applySorting(SpySspInquiryQuery $sspInquiryQuery, ArrayObject $sortCollection): SpySspInquiryQuery
    {
        foreach ($sortCollection as $sort) {
             $sspInquiryQuery->orderBy($sort->getFieldOrFail(), $sort->getIsAscending() ? Criteria::ASC : Criteria::DESC);
        }

        return $sspInquiryQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null): Collection
    {
        if ($paginationTransfer === null) {
            return $query->find();
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPageOrFail();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPageOrFail();

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

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByIdCompanyUser(int $idCompanyUser): CustomerTransfer
    {
        $customerEntity = $this->getFactory()->createCustomerQuery()
            ->useCompanyUserQuery()
                ->filterByIdCompanyUser($idCompanyUser)
            ->endUse()
            ->findOne();

        if (!$customerEntity) {
            return new CustomerTransfer();
        }

        return (new CustomerTransfer())->fromArray($customerEntity->toArray(), true);
    }
}
