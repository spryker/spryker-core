<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business\Model;

use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNotFoundException;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;

class Installer implements InstallerInterface
{

    /**
     * @var Group
     */
    private $group;

    /**
     * @var Role
     */
    private $role;

    /**
     * @var Rule
     */
    private $rule;

    /**
     * @var UserFacade
     */
    private $facadeUser;

    /**
     * @var AclConfig
     */
    protected $config;

    /**
     * @param GroupInterface $group
     * @param RoleInterface $role
     * @param RuleInterface $rule
     * @param UserFacade $facadeUser
     * @param AclConfig $settings
     */
    public function __construct(
        GroupInterface $group,
        RoleInterface $role,
        RuleInterface $rule,
        UserFacade $facadeUser,
        AclConfig $settings
    ) {
        $this->group = $group;
        $this->role = $role;
        $this->rule = $rule;
        $this->facadeUser = $facadeUser;
        $this->config = $settings;
    }

    /**
     * Main Installation Method
     */
    public function install()
    {
        $this->addGroups();
        $this->addRoles();
        $this->addRules();
        $this->addUserGroupRelations();
    }

    private function addGroups()
    {
        foreach ($this->config->getInstallerGroups() as $group) {
            $this->addGroup($group['name']);
        }
    }

    /**
     * @param string $name
     */
    private function addGroup($name)
    {
        if (!$this->group->hasGroupName($name)) {
            $this->group->addGroup($name);
        }
    }

    private function addRoles()
    {
        foreach ($this->config->getInstallerRoles() as $role) {
            if (!$this->role->hasRoleName($role['name'])) {
                $this->addRole($role);
            }
        }
    }

    /**
     * @param array $role
     *
     * @throws GroupNotFoundException
     */
    private function addRole(array $role)
    {
        $group = $this->group->getByName($role['group']);
        if (!$group) {
            throw new GroupNotFoundException();
        }

        $this->role->addRole($role['name'], $group->getIdAclGroup());
    }

    /**
     * @throws RoleNotFoundException
     */
    private function addRules()
    {
        foreach ($this->config->getInstallerRules() as $rule) {
            $role = $this->role->getByName($rule['role']);
            if (!$role) {
                throw new RoleNotFoundException();
            }

            if (!$this->rule->existsRoleRule($role->getIdAclRole(), $rule['bundle'], $rule['controller'], $rule['action'], $rule['type'])) {
                $this->rule->addRule(
                    $rule['bundle'], $rule['controller'], $rule['action'], $role->getIdAclRole(), $rule['type']
                );
            }
        }
    }

    /**
     * @throws GroupNotFoundException
     * @throws UserNotFoundException
     */
    private function addUserGroupRelations()
    {
        foreach ($this->config->getInstallerUsers() as $username => $config) {
            $group = $this->group->getByName($config['group']);
            if (!$group) {
                throw new GroupNotFoundException();
            }

            $user = $this->facadeUser->getUserByUsername($username);
            if (!$user) {
                throw new UserNotFoundException();
            }

            if (!$this->group->hasUser($group->getIdAclGroup(), $user->getIdUserUser())) {
                $this->group->addUser($user->getIdUserUser(), $group->getIdAclGroup());
            }
        }
    }

}
