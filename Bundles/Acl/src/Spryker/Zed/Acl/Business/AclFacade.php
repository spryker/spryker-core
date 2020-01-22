<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Acl\Business\AclBusinessFactory getFactory()
 */
class AclFacade extends AbstractFacade implements AclFacadeInterface
{
    /**
     * Main Installer Method
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()->createInstallerModel()->install();
    }

    /**
     * @api
     *
     * @param string $groupName
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function addGroup($groupName, RolesTransfer $rolesTransfer)
    {
        $groupTransfer = $this->getFactory()
            ->createGroupModel()
            ->addGroup($groupName);

        if (!empty($rolesTransfer)) {
            $this->addRolesToGroup($groupTransfer, $rolesTransfer);
        }

        return $groupTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupTransfer $transfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function updateGroup(GroupTransfer $transfer, RolesTransfer $rolesTransfer)
    {
        $groupTransfer = $this->getFactory()
            ->createGroupModel()
            ->updateGroup($transfer);

        if (!empty($rolesTransfer)) {
            $this->addRolesToGroup($groupTransfer, $rolesTransfer);
        }

        return $groupTransfer;
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroup($id)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getGroupById($id);
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function getGroupByName($name)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getByName($name);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getAllGroups()
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getAllGroups();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getFactory()->getUserFacade()->hasCurrentUser();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->getFactory()->getUserFacade()->getCurrentUser();
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function existsRoleByName($name)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->hasRoleName($name);
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleById($id)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getRoleById($id);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RoleTransfer|null
     */
    public function findRoleById(int $id): ?RoleTransfer
    {
        return $this->getFactory()
            ->createRoleModel()
            ->findRoleById($id);
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function getRoleByName($name)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getByName($name);
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function addRole($name)
    {
        return $this->getFactory()->createRoleModel()->addRole($name);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function updateRole(RoleTransfer $roleTransfer)
    {
        return $this->getFactory()->createRoleModel()->save($roleTransfer);
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function getRule($id)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->getRuleById($id);
    }

    /**
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup($idUser, $idGroup)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->addUser($idGroup, $idUser);
    }

    /**
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return bool
     */
    public function userHasGroupId($idUser, $idGroup)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->hasUser($idGroup, $idUser);
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasGroupByName($name)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->hasGroupName($name);
    }

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\GroupsTransfer
     */
    public function getUserGroups($idUser)
    {
        return $this->getFactory()->createGroupModel()->getUserGroups($idUser);
    }

    /**
     * @api
     *
     * @param int $idUser
     * @param int $idGroup
     *
     * @return void
     */
    public function removeUserFromGroup($idUser, $idGroup)
    {
        $this->getFactory()
            ->createGroupModel()
            ->removeUser($idGroup, $idUser);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->addRule($ruleTransfer);
    }

    /**
     * @api
     *
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getGroupRoles($idGroup)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getGroupRoles($idGroup);
    }

    /**
     * @api
     *
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function getGroupRules($idGroup)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->getRulesForGroupId($idGroup);
    }

    /**
     * @api
     *
     * @param int $idRole
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function getRoleRules($idRole)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->getRoleRules($idRole);
    }

    /**
     * @api
     *
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param int $type
     *
     * @return bool
     */
    public function existsRoleRule($idAclRole, $bundle, $controller, $action, $type)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->existsRoleRule($idAclRole, $bundle, $controller, $action, $type);
    }

    /**
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function getUserRoles($idUser)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getUserRoles($idUser);
    }

    /**
     * @api
     *
     * @param int $idGroup
     *
     * @return bool
     */
    public function removeGroup($idGroup)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->removeGroupById($idGroup);
    }

    /**
     * @api
     *
     * @param int $idRole
     *
     * @return bool
     */
    public function removeRole($idRole)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->removeRoleById($idRole);
    }

    /**
     * @api
     *
     * @param int $idRule
     *
     * @return bool
     */
    public function removeRule($idRule)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->removeRuleById($idRule);
    }

    /**
     * @api
     *
     * @param int $idRole
     * @param int $idGroup
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->addRoleToGroup($idRole, $idGroup);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return void
     */
    public function addRolesToGroup(GroupTransfer $groupTransfer, RolesTransfer $rolesTransfer)
    {
        $groupModel = $this->getFactory()->createGroupModel();
        $groupModel->removeRolesFromGroup($groupTransfer->getIdAclGroup());

        foreach ($rolesTransfer->getRoles() as $roleTransfer) {
            if ($roleTransfer->getIdAclRole() > 0) {
                $groupModel->addRoleToGroup($roleTransfer->getIdAclRole(), $groupTransfer->getIdAclGroup());
            }
        }
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function checkAccess(UserTransfer $user, $bundle, $controller, $action)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->isAllowed($user, $bundle, $controller, $action);
    }

    /**
     * @api
     *
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->isIgnorable($bundle, $controller, $action);
    }
}
