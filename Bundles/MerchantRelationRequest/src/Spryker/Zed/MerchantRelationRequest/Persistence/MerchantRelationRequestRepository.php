<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestSearchConditionsTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\MerchantRelationRequest\Persistence\Map\SpyMerchantRelationRequestTableMap;
use Orm\Zed\MerchantRelationRequest\Persistence\Map\SpyMerchantRelationRequestToCompanyBusinessUnitTableMap;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestPersistenceFactory getFactory()
 */
class MerchantRelationRequestRepository extends AbstractRepository implements MerchantRelationRequestRepositoryInterface
{
    /**
     * @module Merchant
     * @module CompanyUser
     * @module Customer
     * @module CompanyBusinessUnit
     * @module Company
     * @module Customer
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer {
        $merchantRelationRequestCollectionTransfer = new MerchantRelationRequestCollectionTransfer();

        /** @var \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery $merchantRelationRequestQuery */
        $merchantRelationRequestQuery = $this->getFactory()->getMerchantRelationRequestQuery();

        // @phpstan-ignore-next-line
        $merchantRelationRequestQuery
            ->joinWithMerchant()
            ->joinWithCompanyUser()
            ->useCompanyUserQuery()
                ->joinWithCustomer()
            ->endUse()
            ->joinWithCompanyBusinessUnit()
            ->useCompanyBusinessUnitQuery()
                ->joinWithCompany()
            ->endUse();

        $merchantRelationRequestQuery = $this->applyMerchantRelationRequestFilters(
            $merchantRelationRequestQuery,
            $merchantRelationRequestCriteriaTransfer,
        );
        $merchantRelationRequestQuery = $this->applyMerchantRelationRequestSearch(
            $merchantRelationRequestQuery,
            $merchantRelationRequestCriteriaTransfer,
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $merchantRelationRequestCriteriaTransfer->getSortCollection();
        $merchantRelationRequestQuery = $this->applySorting($merchantRelationRequestQuery, $sortTransfers);

        $paginationTransfer = $merchantRelationRequestCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $merchantRelationRequestQuery = $this->applyPagination($merchantRelationRequestQuery, $paginationTransfer);
            $merchantRelationRequestCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createMerchantRelationRequestMapper()
            ->mapMerchantRelationRequestEntitiesToMerchantRelationRequestCollectionTransfer(
                $merchantRelationRequestQuery->find(),
                $merchantRelationRequestCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return int
     */
    public function countMerchantRelationRequests(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): int {
        $merchantRelationRequestQuery = $this->getFactory()->getMerchantRelationRequestQuery();
        $merchantRelationRequestQuery = $this->applyMerchantRelationRequestFilters(
            $merchantRelationRequestQuery,
            $merchantRelationRequestCriteriaTransfer,
        );

        return $merchantRelationRequestQuery->count();
    }

    /**
     * @module CompanyBusinessUnit
     *
     * @param list<int> $merchantRelationRequestIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>>
     */
    public function getAssigneeCompanyBusinessUnitsGroupedByIdMerchantRelationRequest(
        array $merchantRelationRequestIds
    ): array {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnit> $merchantRelationRequestToCompanyBusinessUnitEntities */
        $merchantRelationRequestToCompanyBusinessUnitEntities = $this->getFactory()
            ->getMerchantRelationRequestToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationRequest_In($merchantRelationRequestIds)
            ->joinWithCompanyBusinessUnit()
            ->find();

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapMerchantRelationRequestToCompanyBusinessUnitEntitiesToCompanyBusinessUnitTransfers(
                $merchantRelationRequestToCompanyBusinessUnitEntities,
            );
    }

    /**
     * @module CompanyBusinessUnit
     *
     * @param \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery $merchantRelationRequestQuery
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    protected function applyMerchantRelationRequestFilters(
        SpyMerchantRelationRequestQuery $merchantRelationRequestQuery,
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): SpyMerchantRelationRequestQuery {
        $merchantRelationRequestConditionsTransfer = $merchantRelationRequestCriteriaTransfer->getMerchantRelationRequestConditions();

        if (!$merchantRelationRequestConditionsTransfer) {
            return $merchantRelationRequestQuery;
        }

        if ($merchantRelationRequestConditionsTransfer->getMerchantRelationRequestIds()) {
            $merchantRelationRequestQuery->filterByIdMerchantRelationRequest_In(
                $merchantRelationRequestConditionsTransfer->getMerchantRelationRequestIds(),
            );
        }

        if ($merchantRelationRequestConditionsTransfer->getUuids()) {
            $merchantRelationRequestQuery->filterByUuid_In(
                $merchantRelationRequestConditionsTransfer->getUuids(),
            );
        }

        if ($merchantRelationRequestConditionsTransfer->getStatuses()) {
            $merchantRelationRequestQuery->filterByStatus_In(
                $merchantRelationRequestConditionsTransfer->getStatuses(),
            );
        }

        if ($merchantRelationRequestConditionsTransfer->getCompanyIds()) {
            $merchantRelationRequestQuery
                ->useCompanyBusinessUnitQuery()
                    ->filterByFkCompany_In($merchantRelationRequestConditionsTransfer->getCompanyIds())
                ->endUse();
        }

        if ($merchantRelationRequestConditionsTransfer->getMerchantIds()) {
            $merchantRelationRequestQuery->filterByFkMerchant_In(
                $merchantRelationRequestConditionsTransfer->getMerchantIds(),
            );
        }

        if ($merchantRelationRequestConditionsTransfer->getCompanyUserIds()) {
            $merchantRelationRequestQuery->filterByFkCompanyUser_In(
                $merchantRelationRequestConditionsTransfer->getCompanyUserIds(),
            );
        }

        if ($merchantRelationRequestConditionsTransfer->getOwnerCompanyBusinessUnitIds()) {
            $merchantRelationRequestQuery->filterByFkCompanyBusinessUnit_In(
                $merchantRelationRequestConditionsTransfer->getOwnerCompanyBusinessUnitIds(),
            );
        }

        $criteriaRangeFilterTransfer = $merchantRelationRequestConditionsTransfer->getRangeCreatedAt();

        if (!$criteriaRangeFilterTransfer) {
            return $merchantRelationRequestQuery;
        }

        if ($criteriaRangeFilterTransfer->getFrom()) {
            $merchantRelationRequestQuery->filterByCreatedAt(
                $criteriaRangeFilterTransfer->getFrom(),
                Criteria::GREATER_EQUAL,
            );
        }

        if ($criteriaRangeFilterTransfer->getTo()) {
            $merchantRelationRequestQuery->filterByCreatedAt(
                $criteriaRangeFilterTransfer->getTo(),
                Criteria::LESS_THAN,
            );
        }

        return $merchantRelationRequestQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(
        ModelCriteria $modelCriteria,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            return $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer->setNbResults($propelModelPager->getNbResults())
                ->setFirstIndex($propelModelPager->getFirstIndex())
                ->setLastIndex($propelModelPager->getLastIndex())
                ->setFirstPage($propelModelPager->getFirstPage())
                ->setLastPage($propelModelPager->getLastPage())
                ->setNextPage($propelModelPager->getNextPage())
                ->setPreviousPage($propelModelPager->getPreviousPage());

            return $propelModelPager->getQuery();
        }

        return $modelCriteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(
        ModelCriteria $modelCriteria,
        ArrayObject $sortTransfers
    ): ModelCriteria {
        foreach ($sortTransfers as $sortTransfer) {
            $modelCriteria->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $modelCriteria;
    }

    /**
     * @param \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery $merchantRelationRequestQuery
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    protected function applyMerchantRelationRequestSearch(
        SpyMerchantRelationRequestQuery $merchantRelationRequestQuery,
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): SpyMerchantRelationRequestQuery {
        $merchantRelationRequestSearchConditionsTransfer = $merchantRelationRequestCriteriaTransfer
            ->getMerchantRelationRequestSearchConditions();

        if (!$merchantRelationRequestSearchConditionsTransfer) {
            return $merchantRelationRequestQuery;
        }

        $conditions = [];

        if ($merchantRelationRequestSearchConditionsTransfer->getOwnerCompanyBusinessUnitName()) {
            $conditions[] = MerchantRelationRequestSearchConditionsTransfer::OWNER_COMPANY_BUSINESS_UNIT_NAME;
            $merchantRelationRequestQuery->condition(
                MerchantRelationRequestSearchConditionsTransfer::OWNER_COMPANY_BUSINESS_UNIT_NAME,
                sprintf('%s LIKE ?', sprintf('LOWER(%s)', SpyCompanyBusinessUnitTableMap::COL_NAME)),
                sprintf('%%%s%%', mb_strtolower($merchantRelationRequestSearchConditionsTransfer->getOwnerCompanyBusinessUnitName())),
            );
        }

        if ($merchantRelationRequestSearchConditionsTransfer->getOwnerCompanyBusinessUnitCompanyName()) {
            $conditions[] = MerchantRelationRequestSearchConditionsTransfer::OWNER_COMPANY_BUSINESS_UNIT_COMPANY_NAME;
            $merchantRelationRequestQuery->condition(
                MerchantRelationRequestSearchConditionsTransfer::OWNER_COMPANY_BUSINESS_UNIT_COMPANY_NAME,
                sprintf('%s LIKE ?', sprintf('LOWER(%s)', SpyCompanyTableMap::COL_NAME)),
                sprintf('%%%s%%', mb_strtolower($merchantRelationRequestSearchConditionsTransfer->getOwnerCompanyBusinessUnitCompanyName())),
            );
        }

        if ($merchantRelationRequestSearchConditionsTransfer->getAssigneeCompanyBusinessUnitName()) {
            $conditions[] = MerchantRelationRequestSearchConditionsTransfer::ASSIGNEE_COMPANY_BUSINESS_UNIT_NAME;

            $merchantRelationRequestQuery->condition(
                MerchantRelationRequestSearchConditionsTransfer::ASSIGNEE_COMPANY_BUSINESS_UNIT_NAME,
                sprintf('%s LIKE ?', sprintf('(%s)', $this->getMerchantRelationRequestToCompanyBusinessUnitQuerySql())),
                sprintf('%%%s%%', mb_strtolower($merchantRelationRequestSearchConditionsTransfer->getAssigneeCompanyBusinessUnitName())),
                PDO::PARAM_STR,
            );
        }

        if ($conditions) {
            $merchantRelationRequestQuery->combine($conditions, Criteria::LOGICAL_OR);
        }

        return $merchantRelationRequestQuery;
    }

    /**
     * @return string
     */
    protected function getMerchantRelationRequestToCompanyBusinessUnitQuerySql(): string
    {
        /** @var literal-string $where */
        $where = sprintf(
            '%s = %s',
            SpyMerchantRelationRequestToCompanyBusinessUnitTableMap::COL_FK_MERCHANT_RELATION_REQUEST,
            SpyMerchantRelationRequestTableMap::COL_ID_MERCHANT_RELATION_REQUEST,
        );
        $merchantRelationRequestToCompanyBusinessUnitQuery = $this->getFactory()
            ->getMerchantRelationRequestToCompanyBusinessUnitQuery()
            ->joinCompanyBusinessUnit()
            ->addAsColumn('companyBusinessUnitNames', sprintf('LOWER(GROUP_CONCAT(%s))', SpyCompanyBusinessUnitTableMap::COL_NAME))
            ->where($where);

        $params = [];

        return $merchantRelationRequestToCompanyBusinessUnitQuery->createSelectSql($params);
    }
}
