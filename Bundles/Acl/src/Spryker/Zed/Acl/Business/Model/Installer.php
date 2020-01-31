<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\Acl\Business\Acl\AclMapperInterface;
use Spryker\Zed\Acl\Business\Exception\GroupNotFoundException;
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
     * @var \Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface[]
     */
    protected $aclInstallerPlugins;

    /**
     * @var \Spryker\Zed\Acl\Business\Acl\AclMapperInterface
     */
    protected $aclMapper;

    /**
     * @param \Spryker\Zed\Acl\Business\Model\GroupInterface $group
     * @param \Spryker\Zed\Acl\Business\Model\RoleInterface $role
     * @param \Spryker\Zed\Acl\Business\Model\RuleInterface $rule
     * @param \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface $userFacade
     * @param \Spryker\Zed\Acl\Business\Acl\AclMapperInterface $aclMapper
     * @param \Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface[] $aclInstallerPlugins
     */
    public function __construct(
        GroupInterface $group,
        RoleInterface $role,
        RuleInterface $rule,
        AclToUserInterface $userFacade,
        AclMapperInterface $aclMapper,
        array $aclInstallerPlugins
    ) {
        $this->group = $group;
        $this->role = $role;
        $this->rule = $rule;
        $this->userFacade = $userFacade;
        $this->aclInstallerPlugins = $aclInstallerPlugins;
        $this->aclMapper = $aclMapper;
    }

    /**
     * Main Installation Method
     *
     * @return void
     */
    public function install()
    {
        $this->installGroups();
        $this->installRoles();
        $this->installUserGroupRelations();
    }

    /**
     * @return void
     */
    protected function installGroups(): void
    {
        foreach ($this->getGroups() as $groupTransfer) {
            if ($this->group->hasGroupName($groupTransfer->getName())) {
                continue;
            }
            $this->group->save($groupTransfer);
        }
    }

    /**
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     *
     * @return void
     */
    protected function installRoles(): void
    {
        foreach ($this->getRoles() as $roleTransferToInstall) {
            if (!$this->role->hasRoleName($roleTransferToInstall->getName())) {
                $group = $this->group->getByName($roleTransferToInstall->getAclGroup()->getName());
                if (!$group->getIdAclGroup()) {
                    throw new GroupNotFoundException(sprintf('The group with name %s was not found', $roleTransferToInstall->getAclGroup()->getName()));
                }
                $group = $this->group->getByName($roleTransferToInstall->getAclGroup()->getName());
                $roleTransfer = $this->role->addRole($roleTransferToInstall->getName());
                $this->group->addRoleToGroup($roleTransfer->getIdAclRole(), $group->getIdAclGroup());
            } else {
                $roleTransfer = $this->role->getByName($roleTransferToInstall->getName());
            }

            foreach ($roleTransferToInstall->getAclRules() as $ruleTransfer) {
                $this->addRuleToRole($ruleTransfer, $roleTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return void
     */
    protected function addRuleToRole(RuleTransfer $ruleTransfer, RoleTransfer $roleTransfer): void
    {
        $existsRoleRule = $this->rule->existsRoleRule(
            $roleTransfer->getIdAclRole(),
            $ruleTransfer->getBundle(),
            $ruleTransfer->getController(),
            $ruleTransfer->getAction(),
            $ruleTransfer->getType()
        );
        if ($existsRoleRule) {
            return;
        }
        $ruleTransfer->setFkAclRole($roleTransfer->getIdAclRole());
        $this->rule->addRule($ruleTransfer);
    }

    /**
     * @throws \Spryker\Zed\Acl\Business\Exception\GroupNotFoundException
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return void
     */
    protected function installUserGroupRelations(): void
    {
        foreach ($this->aclMapper->getUserGroupRelations() as $userTransfer) {
            foreach ($userTransfer->getAclGroups() as $groupTransfer) {
                $foundGroupTransfer = $this->group->getByName($groupTransfer->getName());
                if (!$foundGroupTransfer->getIdAclGroup()) {
                    throw new GroupNotFoundException(sprintf('The group with name %s was not found', $groupTransfer->getName()));
                }
                $foundUserTransfer = $this->userFacade->getUserByUsername($userTransfer->getUsername());
                if (!$foundUserTransfer->getIdUser()) {
                    throw new UserNotFoundException(sprintf('The group with name %s was not found', $userTransfer->getUsername()));
                }

                if ($this->group->hasUser($foundGroupTransfer->getIdAclGroup(), $foundUserTransfer->getIdUser())) {
                    continue;
                }
                $this->group->addUser($foundGroupTransfer->getIdAclGroup(), $foundUserTransfer->getIdUser());
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    protected function getRoles(): array
    {
        $roleTransfers = $this->aclMapper->getRoles();

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getRoles() as $roleTransfer) {
                $roleTransfers[] = $roleTransfer;
            }
        }

        return $roleTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    protected function getGroups(): array
    {
        $groupTransfers = $this->aclMapper->getGroups();

        foreach ($this->aclInstallerPlugins as $aclInstallerPlugin) {
            foreach ($aclInstallerPlugin->getGroups() as $groupTransfer) {
                $groupTransfers[] = $groupTransfer;
            }
        }

        return $groupTransfers;
    }
}
