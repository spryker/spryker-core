<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Business;

use Generated\Shared\Transfer\GroupsTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RulesTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;

/**
 * @method AclBusinessFactory getFactory()
 */
class AclFacade extends AbstractFacade
{

    /**
     * Main Installer Method
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()->createInstallerModel()->install();
    }

    /**
     * @param string $groupName
     * @param RolesTransfer $rolesTransfer
     *
     * @return GroupTransfer
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
     * @param GroupTransfer $transfer
     * @param RolesTransfer $rolesTransfer
     *
     * @return GroupTransfer
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
     * @param int $id
     *
     * @return GroupTransfer
     */
    public function getGroup($id)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getGroupById($id);
    }

    /**
     * @param string $name
     *
     * @return GroupTransfer
     */
    public function getGroupByName($name)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getByName($name);
    }

    /**
     * @return GroupsTransfer
     */
    public function getAllGroups()
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getAllGroups();
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->getFactory()->getUserFacade()->hasCurrentUser();
    }

    /**
     * @return UserTransfer
     */
    public function getCurrentUser()
    {
        return $this->getFactory()->getUserFacade()->getCurrentUser();
    }

    /**
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
     * @param int $id
     *
     * @return RoleTransfer
     */
    public function getRoleById($id)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getRoleById($id);
    }

    /**
     * @param string $name
     *
     * @return RoleTransfer
     */
    public function getRoleByName($name)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getByName($name);
    }

    /**
     * @param string $name
     *
     * @return RoleTransfer
     */
    public function addRole($name)
    {
        return $this->getFactory()->createRoleModel()->addRole($name);
    }

    /**
     * @param RoleTransfer $roleTransfer
     *
     * @return RoleTransfer
     */
    public function updateRole(RoleTransfer $roleTransfer)
    {
        return $this->getFactory()->createRoleModel()->save($roleTransfer);
    }

    /**
     * @param int $id
     *
     * @return RuleTransfer
     */
    public function getRule($id)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->getRuleById($id);
    }

    /**
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
     * @param int $idUser
     *
     * @return GroupTransfer
     */
    public function getUserGroup($idUser)
    {
        return $this->getFactory()
            ->createGroupModel()
            ->getUserGroup($idUser);
    }

    /**
     * @param int $idUser
     *
     * @return GroupsTransfer
     */
    public function getUserGroups($idUser)
    {
        return $this->getFactory()->createGroupModel()->getUserGroups($idUser);
    }

    /**
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
     * @param RuleTransfer $ruleTransfer
     *
     * @return RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->addRule($ruleTransfer);
    }

    /**
     * @param int $idGroup
     *
     * @return RolesTransfer
     */
    public function getGroupRoles($idGroup)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getGroupRoles($idGroup);
    }

    /**
     * @param int $idGroup
     *
     * @return RulesTransfer
     */
    public function getGroupRules($idGroup)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->getRulesForGroupId($idGroup);
    }

    /**
     * @param int $idRole
     *
     * @return RulesTransfer
     */
    public function getRoleRules($idRole)
    {
        return $this->getFactory()
            ->createRuleModel()
            ->getRoleRules($idRole);
    }

    /**
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
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
     * @param int $idUser
     *
     * @return RoleTransfer
     */
    public function getUserRoles($idUser)
    {
        return $this->getFactory()
            ->createRoleModel()
            ->getUserRoles($idUser);
    }

    /**
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
     * @param GroupTransfer $groupTransfer
     * @param RolesTransfer $rolesTransfer
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
     * @param UserTransfer $user
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
