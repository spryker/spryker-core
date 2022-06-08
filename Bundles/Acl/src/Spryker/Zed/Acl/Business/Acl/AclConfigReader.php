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
    /**
     * @var string
     */
    protected const GROUP_KEY = 'group';

    /**
     * @var string
     */
    protected const ROLE_KEY = 'role';

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
     * @return array<\Generated\Shared\Transfer\RoleTransfer>
     */
    public function getRoles(): array
    {
        $roleTransfers = [];
        foreach ($this->aclConfig->getInstallerRoles() as $roleData) {
            $groupTransfer = (new GroupTransfer())->setName($roleData[static::GROUP_KEY]);
            $roleTransfers[$roleData[RoleTransfer::NAME]] = (new RoleTransfer())
                ->fromArray($roleData, true)
                ->setAclGroup($groupTransfer);

            if (isset($roleData[RoleTransfer::ACL_ENTITY_RULES])) {
                $roleTransfers[$roleData[RoleTransfer::NAME]]->setAclEntityRules($roleData[RoleTransfer::ACL_ENTITY_RULES]);
            }

            if (isset($roleData[RoleTransfer::ACL_RULES])) {
                $roleTransfers[$roleData[RoleTransfer::NAME]]->setAclRules($roleData[RoleTransfer::ACL_RULES]);
            }
        }
        foreach ($this->aclConfig->getInstallerRules() as $ruleData) {
            if (!isset($roleTransfers[$ruleData[static::ROLE_KEY]])) {
                continue;
            }
            $roleTransfer = $roleTransfers[$ruleData[static::ROLE_KEY]];
            $roleTransfer->addAclRule(
                (new RuleTransfer())->fromArray($ruleData, true),
            );
        }

        return array_values($roleTransfers);
    }

    /**
     * @return array<\Generated\Shared\Transfer\GroupTransfer>
     */
    public function getGroups(): array
    {
        $groupTransfers = [];
        foreach ($this->aclConfig->getInstallerGroups() as $groupData) {
            $groupTransfers[] = (new GroupTransfer())->fromArray($groupData, true);
        }

        return $groupTransfers;
    }

    /**
     * @return array<\Generated\Shared\Transfer\UserTransfer>
     */
    public function getUserGroupRelations(): array
    {
        $userTransfers = [];
        foreach ($this->aclConfig->getInstallerUsers() as $username => $userData) {
            $userTransfer = (new UserTransfer())
                ->setUsername($username);
            if (isset($userData[static::GROUP_KEY])) {
                $groupTransfer = (new GroupTransfer())->setName($userData[static::GROUP_KEY]);
                $userTransfer->addAclGroup($groupTransfer);
            }
            $userTransfers[] = $userTransfer;
        }

        return $userTransfers;
    }
}
