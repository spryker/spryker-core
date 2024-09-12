<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCollectionTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory getFactory()
 */
class AclEntityRepository extends AbstractRepository implements AclEntityRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRulesByRoles(RolesTransfer $rolesTransfer): AclEntityRuleCollectionTransfer
    {
        $roleIds = $this->getRoleIdsFromRolesTransfer($rolesTransfer);

        $aclEntityRuleEntities = $this->getFactory()
            ->createAclEntityRuleQuery()
            ->filterByFkAclRole_In($roleIds)
            ->find();

        return $this->getFactory()
            ->createAclEntityRuleMapper()
            ->mapAclEntityRuleCollectionToAclEntityRuleCollectionTransfer(
                $aclEntityRuleEntities,
                new AclEntityRuleCollectionTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRuleCollection(AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer): AclEntityRuleCollectionTransfer
    {
        $aclEntityRuleQuery = $this->getFactory()->createAclEntityRuleQuery();
        $aclEntityRuleQuery = $this->applyAclEntityRuleFilters($aclEntityRuleQuery, $aclEntityRuleCriteriaTransfer);
        $aclEntityRuleQuery = $this->applySorting($aclEntityRuleQuery, $aclEntityRuleCriteriaTransfer->getSortCollection());

        $aclEntityRuleCollectionTransfer = new AclEntityRuleCollectionTransfer();
        $paginationTransfer = $aclEntityRuleCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $aclEntityRuleQuery = $this->applyPagination($aclEntityRuleQuery, $paginationTransfer);
            $aclEntityRuleCollectionTransfer->setPagination($paginationTransfer);
        }

        $aclEntityRuleEntities = $aclEntityRuleQuery->find();

        return $this->getFactory()
            ->createAclEntityRuleMapper()
            ->mapAclEntityRuleCollectionToAclEntityRuleCollectionTransfer(
                $aclEntityRuleEntities,
                $aclEntityRuleCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule> $aclEntityRuleQuery
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule>
     */
    protected function applyAclEntityRuleFilters(
        SpyAclEntityRuleQuery $aclEntityRuleQuery,
        AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
    ): SpyAclEntityRuleQuery {
        $aclEntityRuleCriteriaConditions = $aclEntityRuleCriteriaTransfer->getAclEntityRuleCriteriaConditions();
        if ($aclEntityRuleCriteriaConditions === null) {
            return $this->applyDeprecatedAclEntityRuleFilters($aclEntityRuleQuery, $aclEntityRuleCriteriaTransfer);
        }

        if ($aclEntityRuleCriteriaConditions->getAclRoleIds() !== []) {
            $aclEntityRuleQuery->filterByFkAclRole_In(array_unique($aclEntityRuleCriteriaConditions->getAclRoleIds()));
        }

        if ($aclEntityRuleCriteriaConditions->getEntities() !== []) {
            $aclEntityRuleQuery->filterByEntity_In(array_unique($aclEntityRuleCriteriaConditions->getEntities()));
        }

        if ($aclEntityRuleCriteriaConditions->getScopes() !== []) {
            $aclEntityRuleQuery->filterByScope_In(array_unique($aclEntityRuleCriteriaConditions->getScopes()));
        }

        if ($aclEntityRuleCriteriaConditions->getPermissionMasks() !== []) {
            $aclEntityRuleQuery->filterByPermissionMask_In(array_unique($aclEntityRuleCriteriaConditions->getPermissionMasks()));
        }

        if ($aclEntityRuleCriteriaConditions->getAclEntitySegmentIds() !== []) {
            $aclEntityRuleQuery->filterByFkAclEntitySegment_In(array_unique($aclEntityRuleCriteriaConditions->getAclEntitySegmentIds()))
                ->_or()
                ->filterByFkAclEntitySegment(null);
        }

        return $aclEntityRuleQuery;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\AclEntity\Persistence\AclEntityRepository::applyAclEntityRuleFilters()} instead.
     *
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule> $aclEntityRuleQuery
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule>
     */
    protected function applyDeprecatedAclEntityRuleFilters(
        SpyAclEntityRuleQuery $aclEntityRuleQuery,
        AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
    ) {
        if ($aclEntityRuleCriteriaTransfer->getAclRoleIds()) {
            $aclEntityRuleQuery->filterByFkAclRole_In($aclEntityRuleCriteriaTransfer->getAclRoleIds());
        }
        if ($aclEntityRuleCriteriaTransfer->getEntities()) {
            $aclEntityRuleQuery->filterByEntity_In($aclEntityRuleCriteriaTransfer->getEntities());
        }
        if ($aclEntityRuleCriteriaTransfer->getScopes()) {
            $aclEntityRuleQuery->filterByScope_In($aclEntityRuleCriteriaTransfer->getScopes());
        }
        if ($aclEntityRuleCriteriaTransfer->getPermissionMasks()) {
            $aclEntityRuleQuery->filterByPermissionMask_In($aclEntityRuleCriteriaTransfer->getPermissionMasks());
        }
        if ($aclEntityRuleCriteriaTransfer->getAclEntitySegmentIds()) {
            $aclEntityRuleQuery->filterByFkAclEntitySegment_In($aclEntityRuleCriteriaTransfer->getAclEntitySegmentIds());
        }

        return $aclEntityRuleQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return array<int>
     */
    protected function getRoleIdsFromRolesTransfer(RolesTransfer $rolesTransfer): array
    {
        return array_map(
            function (RoleTransfer $roleTransfer): int {
                return $roleTransfer->getIdAclRoleOrFail();
            },
            $rolesTransfer->getRoles()->getArrayCopy(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentCollectionTransfer
     */
    public function getAclEntitySegmentCollection(
        AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
    ): AclEntitySegmentCollectionTransfer {
        $aclEntitySegmentQuery = $this->getFactory()->createAclEntitySegmentQuery();
        $aclEntitySegmentQuery = $this->applyAclEntitySegmentFilters($aclEntitySegmentQuery, $aclEntitySegmentCriteriaTransfer);
        $aclEntitySegmentQuery = $this->applySorting($aclEntitySegmentQuery, $aclEntitySegmentCriteriaTransfer->getSortCollection());

        $aclEntitySegmentCollectionTransfer = new AclEntitySegmentCollectionTransfer();
        $paginationTransfer = $aclEntitySegmentCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $aclEntitySegmentQuery = $this->applyPagination($aclEntitySegmentQuery, $paginationTransfer);
            $aclEntitySegmentCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createAclEntitySegmentMapper()
            ->mapAclEntitySegmentEntityCollectionToAclEntitySegmentCollectionTransfer(
                $aclEntitySegmentQuery->find(),
                $aclEntitySegmentCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery $aclEntitySegmentQuery
     * @param \Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery
     */
    protected function applyAclEntitySegmentFilters(
        SpyAclEntitySegmentQuery $aclEntitySegmentQuery,
        AclEntitySegmentCriteriaTransfer $aclEntitySegmentCriteriaTransfer
    ): SpyAclEntitySegmentQuery {
        $aclEntitySegmentConditionsTransfer = $aclEntitySegmentCriteriaTransfer->getAclEntitySegmentConditions();
        if ($aclEntitySegmentConditionsTransfer === null) {
            return $aclEntitySegmentQuery;
        }

        if ($aclEntitySegmentConditionsTransfer->getAclEntitySegmentIds() !== []) {
            $aclEntitySegmentQuery->filterByIdAclEntitySegment_In($aclEntitySegmentConditionsTransfer->getAclEntitySegmentIds());
        }

        if ($aclEntitySegmentConditionsTransfer->getNames() !== []) {
            $aclEntitySegmentQuery->filterByName_In($aclEntitySegmentConditionsTransfer->getNames());
        }

        if ($aclEntitySegmentConditionsTransfer->getReferences() !== []) {
            $aclEntitySegmentQuery->filterByReference_In($aclEntitySegmentConditionsTransfer->getReferences());
        }

        return $aclEntitySegmentQuery;
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

            $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $modelCriteria;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage() !== null) {
            $paginationModel = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer
                ->setNbResults($paginationModel->getNbResults())
                ->setFirstIndex($paginationModel->getFirstIndex())
                ->setLastIndex($paginationModel->getLastIndex())
                ->setFirstPage($paginationModel->getFirstPage())
                ->setLastPage($paginationModel->getLastPage())
                ->setNextPage($paginationModel->getNextPage())
                ->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getQuery();
        }

        return $modelCriteria;
    }
}
