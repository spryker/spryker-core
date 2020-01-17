<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @var \Spryker\Zed\Acl\Business\Model\GroupInterface
     */
    protected $group;

    /**
     * @var \Spryker\Zed\Acl\Business\Model\RoleInterface
     */
    protected $role;

    /**
     * @var \Spryker\Zed\Acl\Business\Model\RuleInterface
     */
    protected $rule;

    /**
     * @var \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\Acl\AclConfig
     */
    protected $config;

    /**
     * @var array|\Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface[]
     */
    protected $aclInstallerPlugins;

    /**
     * @param \Spryker\Zed\Acl\Business\Model\GroupInterface $group
     * @param \Spryker\Zed\Acl\Business\Model\RoleInterface $role
     * @param \Spryker\Zed\Acl\Business\Model\RuleInterface $rule
     * @param \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface $userFacade
     * @param \Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface[] $aclInstallerPlugins
     * @param \Spryker\Zed\Acl\AclConfig $config
     */
    public function __construct(
        GroupInterface $group,
        RoleInterface $role,
        RuleInterface $rule,
        AclToUserInterface $userFacade,
        array $aclInstallerPlugins,
        AclConfig $config
    ) {
        $this->group = $group;
        $this->role = $role;
        $this->rule = $rule;
        $this->userFacade = $userFacade;
        $this->config = $config;
        $this->aclInstallerPlugins = $aclInstallerPlugins;
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
        foreach ($this->getGroups() as $group) {
            $this->addGroup($group['name']);
        }
    }

    /**
     * @return array
     */
    private function getGroups(): array
    {
        $groups = $this->config->getInstallerGroups();

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getGroups() as $groupTransfer) {
                $groups[] = $groupTransfer->toArray();
            }
        }

        return $groups;
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
        foreach ($this->getRoles() as $role) {
            if (!$this->role->hasRoleName($role['name'])) {
                $this->addRole($role);
            }
        }
    }

    /**
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return array
     */
    private function getRoles(): array
    {
        $roles = $this->config->getInstallerRoles();

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getRoles() as $roleTransfer) {
                if (!$roleTransfer->getGroup()) {
                    throw new GroupNotFoundException();
                }
                $role = $roleTransfer->toArray();
                $role['group'] = $roleTransfer->getGroup()->getName();
                $roles[] = $role;
            }
        }

        return $roles;
    }

    /**
     * @param array $role
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return void
     */
    private function addRole(array $role)
    {
        $group = $this->group->getByName($role['group']);
        if (!$group->getIdAclGroup()) {
            throw new GroupNotFoundException();
        }

        $roleTransfer = $this->role->addRole($role['name']);
        $this->group->addRoleToGroup($roleTransfer->getIdAclRole(), $group->getIdAclGroup());
    }

    /**
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNotFoundException
     *
     * @return void
     */
    private function addRules()
    {
        foreach ($this->getRules() as $rule) {
            $role = $this->role->getByName($rule['role']);
            if (!$role->getIdAclRole()) {
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
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNotFoundException
     *
     * @return array
     */
    private function getRules(): array
    {
        $rules = $this->config->getInstallerRules();

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getRules() as $ruleTransfer) {
                if (!$ruleTransfer->getRole()) {
                    throw new RoleNotFoundException();
                }
                $rule = $ruleTransfer->toArray();
                $rule['role'] = $ruleTransfer->getRole()->getName();
                $rules[] = $rule;
            }
        }

        return $rules;
    }

    /**
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return void
     */
    private function addUserGroupRelations()
    {
        foreach ($this->getUserGroupRelations() as $username => $config) {
            $group = $this->group->getByName($config['group']);
            if (!$group->getIdAclGroup()) {
                throw new GroupNotFoundException();
            }

            $user = $this->userFacade->getUserByUsername($username);
            if (!$user->getIdUser()) {
                throw new UserNotFoundException();
            }

            if (!$this->group->hasUser($group->getIdAclGroup(), $user->getIdUser())) {
                $this->group->addUser($group->getIdAclGroup(), $user->getIdUser());
            }
        }
    }

    /**
     * @return array
     */
    private function getUserGroupRelations(): array
    {
        $users = $this->config->getInstallerUsers();

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getUsers() as $userTransfer) {
                $users[$userTransfer->getUsername()] = ['group' => $userTransfer->getGroup()->getName()];
            }
        }

        return $users;
    }
}
