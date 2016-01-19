<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\Acl\AclConfig;
use Spryker\Zed\Acl\Business\Exception\GroupNotFoundException;
use Spryker\Zed\Acl\Business\Exception\RoleNotFoundException;
use Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

class Installer implements InstallerInterface
{

    /**
     * @var Group
     */
    protected $group;

    /**
     * @var Role
     */
    protected $role;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var AclToUserInterface
     */
    protected $facadeUser;

    /**
     * @var AclConfig
     */
    protected $config;

    /**
     * @param GroupInterface $group
     * @param RoleInterface $role
     * @param RuleInterface $rule
     * @param AclToUserInterface $facadeUser
     * @param AclConfig $settings
     */
    public function __construct(
        GroupInterface $group,
        RoleInterface $role,
        RuleInterface $rule,
        AclToUserInterface $facadeUser,
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
     *
     * @return void
     */
    public function install()
    {
        $this->addGroups();
        $this->addRoles();
        $this->addRules();
        $this->addUserGroupRelations();
    }

    /**
     * @return void
     */
    private function addGroups()
    {
        foreach ($this->config->getInstallerGroups() as $group) {
            $this->addGroup($group['name']);
        }
    }

    /**
     * @param string $name
     *
     * @return void
     */
    private function addGroup($name)
    {
        if (!$this->group->hasGroupName($name)) {
            $this->group->addGroup($name);
        }
    }

    /**
     * @return void
     */
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
     *
     * @return void
     */
    private function addRole(array $role)
    {
        $group = $this->group->getByName($role['group']);
        if (!$group) {
            throw new GroupNotFoundException();
        }

        $roleTransfer = $this->role->addRole($role['name']);
        $this->group->addRoleToGroup($roleTransfer->getIdAclRole(), $group->getIdAclGroup());
    }

    /**
     * @throws RoleNotFoundException
     *
     * @return void
     */
    private function addRules()
    {
        foreach ($this->config->getInstallerRules() as $rule) {
            $role = $this->role->getByName($rule['role']);
            if (!$role) {
                throw new RoleNotFoundException();
            }

            if (!$this->rule->existsRoleRule($role->getIdAclRole(), $rule['bundle'], $rule['controller'], $rule['action'], $rule['type'])) {
                $ruleTransfer = new RuleTransfer();
                $ruleTransfer->fromArray($rule, true);
                $ruleTransfer->setFkAclRole($role->getIdAclRole());
                $this->rule->addRule($ruleTransfer);
            }
        }
    }

    /**
     * @throws GroupNotFoundException
     * @throws UserNotFoundException
     *
     * @return void
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

            if (!$this->group->hasUser($group->getIdAclGroup(), $user->getIdUser())) {
                $this->group->addUser($user->getIdUser(), $group->getIdAclGroup());
            }
        }
    }

}
