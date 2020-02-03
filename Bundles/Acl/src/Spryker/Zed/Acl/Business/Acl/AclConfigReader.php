<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Acl;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Acl\AclConfig;

class AclConfigReader implements AclConfigReaderInterface
{
    protected const GROUP_INDEX = 'group';
    protected const ROLE_INDEX = 'role';

    /**
     * @var \Spryker\Zed\Acl\AclConfig
     */
    protected $aclConfig;

    /**
     * @param \Spryker\Zed\Acl\AclConfig $aclConfig
     */
    public function __construct(AclConfig $aclConfig)
    {
        $this->aclConfig = $aclConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getRoles(): array
    {
        $result = [];
        foreach ($this->aclConfig->getInstallerRoles() as $roleData) {
            $group = (new GroupTransfer())->setName($roleData[static::GROUP_INDEX]);
            $result[$roleData[RoleTransfer::NAME]] = (new RoleTransfer())
                ->setName($roleData[RoleTransfer::NAME])
                ->setAclGroup($group);
        }
        foreach ($this->aclConfig->getInstallerRules() as $ruleData) {
            if (!isset($result[$ruleData[static::ROLE_INDEX]])) {
                continue;
            }
            $roleTransfer = $result[$ruleData[static::ROLE_INDEX]];

            $rule = (new RuleTransfer())
                ->setType($ruleData[RuleTransfer::TYPE])
                ->setAction($ruleData[RuleTransfer::ACTION])
                ->setBundle($ruleData[RuleTransfer::BUNDLE])
                ->setController($ruleData[RuleTransfer::CONTROLLER]);
            $roleTransfer->addAclRule($rule);
        }

        return array_values($result);
    }

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getGroups(): array
    {
        $result = [];
        foreach ($this->aclConfig->getInstallerGroups() as $groupData) {
            $result[] = (new GroupTransfer())->setName($groupData[GroupTransfer::NAME]);
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer[]
     */
    public function getUserGroupRelations(): array
    {
        $result = [];
        foreach ($this->aclConfig->getInstallerUsers() as $username => $userData) {
            $group = (new GroupTransfer())->setName($userData[static::GROUP_INDEX]);
            $result[] = (new UserTransfer())
                ->setUsername($username)
                ->addAclGroup($group);
        }

        return $result;
    }
}
