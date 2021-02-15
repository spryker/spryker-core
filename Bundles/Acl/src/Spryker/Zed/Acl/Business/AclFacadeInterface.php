<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business;

use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AclFacadeInterface
{
    /**
     * Specification:
     * - Main Installer Method
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $groupName
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function addGroup($groupName, RolesTransfer $rolesTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupTransfer $transfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $transfer, RolesTransfer $rolesTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroup($id);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroupByName($name);

    /**
     * Specification:
     * - Returns group transfer by criteria.
     * - Returns NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupCriteriaTransfer $groupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer|null
     */
    public function findGroup(GroupCriteriaTransfer $groupCriteriaTransfer): ?GroupTransfer;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function existsRoleByName($name);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleById($id);

    /**
     * Specification:
     * - Finds role by provided id if it exists.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer|null
     */
    public function findRoleById(int $id): ?RoleTransfer;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleByName($name);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function addRole($name);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function updateRole(RoleTransfer $roleTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function getRule($id);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup($idUser, $idGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return bool
     */
    public function userHasGroupId($idUser, $idGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasGroupByName($name);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function removeUserFromGroup($idUser, $idGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getGroupRoles($idGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function getGroupRules($idGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idRole
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function getRoleRules($idRole);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param string $type
     *
     * @return bool
     */
    public function existsRoleRule($idAclRole, $bundle, $controller, $action, $type);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getUserRoles($idUser);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idGroup
     *
     * @return bool
     */
    public function removeGroup($idGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idRole
     *
     * @return bool
     */
    public function removeRole($idRole);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idRule
     *
     * @return bool
     */
    public function removeRule($idRule);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idRole
     * @param int $idGroup
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return void
     */
    public function addRolesToGroup(GroupTransfer $groupTransfer, RolesTransfer $rolesTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function checkAccess(UserTransfer $user, $bundle, $controller, $action);

    /**
     * Specification:
     * - Iterates through the navigation items collection.
     * - Checks if the navigation item can be accessed by the current user.
     * - Returns the navigation items collection without inaccessible items.
     * - Returns the empty collection in case there is no authorized user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filterNavigationItemCollectionByAccessibility(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action);
}
