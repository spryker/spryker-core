<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
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
     * @var \Spryker\Zed\Acl\Business\Model\AclConverterInterface
     */
    protected $aclConverter;

    /**
     * @param \Spryker\Zed\Acl\Business\Model\GroupInterface $group
     * @param \Spryker\Zed\Acl\Business\Model\RoleInterface $role
     * @param \Spryker\Zed\Acl\Business\Model\RuleInterface $rule
     * @param \Spryker\Zed\Acl\Business\Model\AclConverterInterface $aclConverter
     * @param \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface $userFacade
     * @param \Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface[] $aclInstallerPlugins
     * @param \Spryker\Zed\Acl\AclConfig $config
     */
    public function __construct(
        GroupInterface $group,
        RoleInterface $role,
        RuleInterface $rule,
        AclConverterInterface $aclConverter,
        AclToUserInterface $userFacade,
        array $aclInstallerPlugins,
        AclConfig $config
    ) {
        $this->group = $group;
        $this->role = $role;
        $this->rule = $rule;
        $this->aclConverter = $aclConverter;
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
    private function addGroups(): void
    {
        foreach ($this->getGroups() as $groupTransfer) {
            $this->addGroup($groupTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    private function getGroups(): array
    {
        $groupTransfers = $this->aclConverter->convertGroupArrayToTransfers($this->config->getInstallerGroups());

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getGroups() as $groupTransfer) {
                $groupTransfers[] = $groupTransfer;
            }
        }

        return $groupTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     *
     * @return void
     */
    private function addGroup(GroupTransfer $groupTransfer): void
    {
        if (!$this->group->hasGroupName($groupTransfer->getName())) {
            $this->group->addGroup($groupTransfer->getName());
        }
    }

    /**
     * @return void
     */
    private function addRoles(): void
    {
        foreach ($this->getRoles() as $roleTransfer) {
            if (!$this->role->hasRoleName($roleTransfer->getName())) {
                $this->addRole($roleTransfer);
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    private function getRoles(): array
    {
        $roleTransfers = $this->aclConverter->convertRoleArrayToTransfers($this->config->getInstallerRoles());

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getRoles() as $roleTransfer) {
                $roleTransfers[] = $roleTransfer;
            }
        }

        return $roleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return void
     */
    private function addRole(RoleTransfer $roleTransfer): void
    {
        $group = $this->group->getByName($roleTransfer->getGroup()->getName());
        if (!$group->getIdAclGroup()) {
            throw new GroupNotFoundException();
        }

        $roleTransfer = $this->role->addRole($roleTransfer->getName());
        $this->group->addRoleToGroup($roleTransfer->getIdAclRole(), $group->getIdAclGroup());
    }

    /**
     * @throws \Spryker\Zed\Acl\Business\Exception\RoleNotFoundException
     *
     * @return void
     */
    private function addRules(): void
    {
        foreach ($this->getRules() as $ruleTransfer) {
            $role = $this->role->getByName($ruleTransfer->getRole()->getName());
            if (!$role->getIdAclRole()) {
                throw new RoleNotFoundException();
            }

            $existsRoleRule = $this->rule->existsRoleRule(
                $role->getIdAclRole(),
                $ruleTransfer->getBundle(),
                $ruleTransfer->getController(),
                $ruleTransfer->getAction(),
                $ruleTransfer->getType()
            );
            if (!$existsRoleRule) {
                $ruleTransfer->setFkAclRole($role->getIdAclRole());
                $this->rule->addRule($ruleTransfer);
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\RuleTransfer[]
     */
    private function getRules(): array
    {
        $ruleTransfers = $this->aclConverter->convertRuleArrayToTransfers($this->config->getInstallerRules());

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getRoles() as $roleTransfer) {
                $ruleTransfers[] = $roleTransfer->getRule();
            }
        }

        return $ruleTransfers;
    }

    /**
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return void
     */
    private function addUserGroupRelations(): void
    {
        foreach ($this->getUserGroupRelations() as $userGroupTransfer) {
            $group = $this->group->getByName($userGroupTransfer->getGroup()->getName());
            if (!$group->getIdAclGroup()) {
                throw new GroupNotFoundException();
            }

            $user = $this->userFacade->getUserByUsername($userGroupTransfer->getUser()->getUsername());
            if (!$user->getIdUser()) {
                throw new UserNotFoundException();
            }

            if (!$this->group->hasUser($group->getIdAclGroup(), $user->getIdUser())) {
                $this->group->addUser($group->getIdAclGroup(), $user->getIdUser());
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\UserGroupTransfer[]
     */
    private function getUserGroupRelations(): array
    {
        $userGroupTransfers = $this->aclConverter->convertUserGroupArrayToTransfers($this->config->getInstallerUsers());

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getUserGroups() as $userGroupTransfer) {
                $userGroupTransfers[] = $userGroupTransfer;
            }
        }

        return $userGroupTransfers;
    }
}
