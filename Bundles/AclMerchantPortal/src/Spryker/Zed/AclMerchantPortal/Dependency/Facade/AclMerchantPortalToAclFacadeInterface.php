<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Dependency\Facade;

use Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer;
use Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupsTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;

interface AclMerchantPortalToAclFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer): RuleTransfer;

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function createGroup(GroupTransfer $groupTransfer, RolesTransfer $rolesTransfer): GroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $transfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $transfer, RolesTransfer $rolesTransfer): GroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function createRole(RoleTransfer $roleTransfer): RoleTransfer;

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleByName(string $name): RoleTransfer;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsRoleByName(string $name): bool;

    /**
     * @param \Generated\Shared\Transfer\GroupCriteriaTransfer $groupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaTransfer $groupCriteriaTransfer): ?GroupTransfer;

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup(int $idUser, int $idGroup): int;

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups(int $idUser): GroupsTransfer;

    /**
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getGroupRoles(int $idGroup): RolesTransfer;

    /**
     * @param \Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer $aclUserHasGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer
     */
    public function getAclUserHasGroupCollection(
        AclUserHasGroupCriteriaTransfer $aclUserHasGroupCriteriaTransfer
    ): AclUserHasGroupCollectionTransfer;

    /**
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param string $type
     *
     * @return bool
     */
    public function existsRoleRule(
        int $idAclRole,
        string $bundle,
        string $controller,
        string $action,
        string $type
    ): bool;
}
