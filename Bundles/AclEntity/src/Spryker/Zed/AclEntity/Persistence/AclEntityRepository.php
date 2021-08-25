<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
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
                new AclEntityRuleCollectionTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRules(AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer): AclEntityRuleCollectionTransfer
    {
        $aclEntityRuleQuery = $this->getFactory()->createAclEntityRuleQuery();
        $aclEntityRuleQuery = $this->applyFilters($aclEntityRuleQuery, $aclEntityRuleCriteriaTransfer);
        $aclEntityRuleEntities = $aclEntityRuleQuery->find();

        return $this->getFactory()
            ->createAclEntityRuleMapper()
            ->mapAclEntityRuleCollectionToAclEntityRuleCollectionTransfer(
                $aclEntityRuleEntities,
                new AclEntityRuleCollectionTransfer()
            );
    }

    /**
     * @phpstan-param \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule> $aclEntityRuleQuery
     *
     * @phpstan-return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery<\Orm\Zed\AclEntity\Persistence\SpyAclEntityRule>
     *
     * @param \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery $aclEntityRuleQuery
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery
     */
    protected function applyFilters(
        SpyAclEntityRuleQuery $aclEntityRuleQuery,
        AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
    ): SpyAclEntityRuleQuery {
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
     * @return int[]
     */
    protected function getRoleIdsFromRolesTransfer(RolesTransfer $rolesTransfer): array
    {
        return array_map(
            function (RoleTransfer $roleTransfer): int {
                return $roleTransfer->getIdAclRoleOrFail();
            },
            $rolesTransfer->getRoles()->getArrayCopy()
        );
    }
}
