<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Business;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AclFacadeInterface
{

    /**
     * Main Installer Method
     *
     * @return void
     */
    public function install();

    /**
     * @param string $groupName
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function addGroup($groupName, RolesTransfer $rolesTransfer);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $transfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $transfer, RolesTransfer $rolesTransfer);

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroup($id);

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroupByName($name);

    /**
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups();

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsRoleByName($name);

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleById($id);

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleByName($name);

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function addRole($name);

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function updateRole(RoleTransfer $roleTransfer);

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function getRule($id);

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup($idUser, $idGroup);

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return bool
     */
    public function userHasGroupId($idUser, $idGroup);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasGroupByName($name);

    /**
     * @deprecated Will be removed in 1.0.0.
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getUserGroup($idUser);

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser);

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function removeUserFromGroup($idUser, $idGroup);

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer);

    /**
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getGroupRoles($idGroup);

    /**
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function getGroupRules($idGroup);

    /**
     * @param int $idRole
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function getRoleRules($idRole);

    /**
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function existsRoleRule($idAclRole, $bundle, $controller, $action, $type);

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getUserRoles($idUser);

    /**
     * @param int $idGroup
     *
     * @return bool
     */
    public function removeGroup($idGroup);

    /**
     * @param int $idRole
     *
     * @return bool
     */
    public function removeRole($idRole);

    /**
     * @param int $idRule
     *
     * @return bool
     */
    public function removeRule($idRule);

    /**
     * @param int $idRole
     * @param int $idGroup
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup);

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return void
     */
    public function addRolesToGroup(GroupTransfer $groupTransfer, RolesTransfer $rolesTransfer);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function checkAccess(UserTransfer $user, $bundle, $controller, $action);

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action);

}
