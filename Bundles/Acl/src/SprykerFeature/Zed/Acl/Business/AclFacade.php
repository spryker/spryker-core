<?php

namespace SprykerFeature\Zed\Acl\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\AclGroupTransfer;
use Generated\Shared\Transfer\AclRoleTransfer;
use Generated\Shared\Transfer\AclRuleTransfer;
use Generated\Shared\Transfer\UserUserTransfer;

/**
 * @method AclDependencyContainer getDependencyContainer()
 */
class AclFacade extends AbstractFacade
{
    /**
     * Main Installer Method
     */
    public function install()
    {
        $this->getDependencyContainer()->createInstallerModel()->install();
    }

    /**
     * @param string $name
     *
     * @return Group
     */
    public function addGroup($name)
    {
        return $this->getDependencyContainer()
            ->createGroupModel()
            ->addGroup($name);
    }

    /**
     * @param Group $data
     *
     * @return Group
     */
    public function updateGroup(Group $data)
    {
        return $this->getDependencyContainer()
            ->createGroupModel()
            ->updateGroup($data);
    }

    /**
     * @param int $id
     *
     * @return Group
     */
    public function getGroup($id)
    {
        return $this->getDependencyContainer()
            ->createGroupModel()
            ->getGroupById($id);
    }

    /**
     * @return GroupCollection
     */
    public function getAllGroups()
    {
        return $this->getDependencyContainer()
            ->createGroupModel()
            ->getAllGroups();
    }

    /**
     * @param int $id
     *
     * @return Role
     */
    public function getRole($id)
    {
        return $this->getDependencyContainer()
            ->createRoleModel()
            ->getRoleById($id);
    }

    /**
     * @param int $id
     *
     * @return Rule
     */
    public function getRule($id)
    {
        return $this->getDependencyContainer()
            ->createRuleModel()
            ->getRuleById($id);
    }

    /**
     * @param string $name
     * @param int $idGroup
     *
     * @return Role
     */
    public function addRole($name, $idGroup)
    {
        return $this->getDependencyContainer()
            ->createRoleModel()
            ->addRole($name, $idGroup);
    }

    /**
     * @param int $idRole
     * @param int $idGroup
     *
     * @return int
     */
    public function addRoleToGroup($idRole, $idGroup)
    {
        return $this->getDependencyContainer()
            ->createGroupModel()
            ->addRole($idGroup, $idRole);
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     *
     * @return int
     */
    public function addUserToGroup($idUser, $idGroup)
    {
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
            ->createGroupModel()
            ->hasGroupName($name);
    }

    /**
     * @param $idUser
     *
     * @return Group
     */
    public function getUserGroup($idUser)
    {
        return $this->getDependencyContainer()
            ->createGroupModel()
            ->getUserGroup($idUser);
    }

    /**
     * @param int $idUser
     * @param int $idGroup
     */
    public function removeUserFromGroup($idUser, $idGroup)
    {
        $this->getDependencyContainer()
            ->createGroupModel()
            ->removeUser($idGroup, $idUser);
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param string $idRole
     * @param string $type
     *
     * @return Rule
     */
    public function addRule($bundle, $controller, $action, $idRole, $type = 'allow')
    {
        return $this->getDependencyContainer()
            ->createRuleModel()
            ->addRule($bundle, $controller, $action, $idRole, $type);
    }

    /**
     * @param int $idGroup
     *
     * @return RoleCollection
     */
    public function getGroupRoles($idGroup)
    {
        return $this->getDependencyContainer()
            ->createRoleModel()
            ->getGroupRoles($idGroup);
    }

    /**
     * @param int $idGroup
     *
     * @return RuleCollection
     */
    public function getGroupRules($idGroup)
    {
        return $this->getDependencyContainer()
            ->createRuleModel()
            ->findByGroupId($idGroup);
    }

    /**
     * @param int $idRole
     *
     * @return RuleCollection
     */
    public function getRoleRules($idRole)
    {
        return $this->getDependencyContainer()
            ->createRuleModel()
            ->getRoleRules($idRole);
    }

    /**
     * @param int $idUser
     *
     * @return RoleCollection
     */
    public function getUserRoles($idUser)
    {
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
            ->createRuleModel()
            ->removeRuleById($idRule);
    }

    /**
     * @param User $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function checkAccess(User $user, $bundle, $controller, $action)
    {
        return $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
            ->createRuleModel()
            ->isIgnorable($bundle, $controller, $action);
    }
}
