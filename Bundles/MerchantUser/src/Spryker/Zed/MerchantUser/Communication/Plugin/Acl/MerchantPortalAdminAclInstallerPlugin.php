<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\Acl;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserGroupTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantPortalAdminAclInstallerPlugin extends AbstractPlugin implements AclInstallerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getRoles(): array
    {
        $roleTransfers = [];
        foreach ($this->getConfig()->getInstallRoles() as $roleData) {
            $roleTransfer = (new RoleTransfer())
                ->fromArray($roleData, true)
                ->setGroup((new GroupTransfer())->fromArray($roleData['group'], true));
            $ruleTransfer = (new RuleTransfer())
                ->fromArray($roleData['rule'], true)
                ->setRole((new RoleTransfer())->fromArray($roleData, true));
            $roleTransfers[] = $roleTransfer->setRule($ruleTransfer);
        }

        return $roleTransfers;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getGroups(): array
    {
        $groupTransfers = [];
        foreach ($this->getConfig()->getInstallGroups() as $groupData) {
            $groupTransfers[] = (new GroupTransfer())->fromArray($groupData, true);
        }

        return $groupTransfers;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\UserGroupTransfer[]
     */
    public function getUserGroups(): array
    {
        $userGroupTransfers = [];
        foreach ($this->getConfig()->getInstallUserGroups() as $userGroupData) {
            $userGroupTransfers[] = (new UserGroupTransfer())
                ->setUser((new UserTransfer())->fromArray($userGroupData['user']))
                ->setGroup((new GroupTransfer())->fromArray($userGroupData['group']));
        }

        return $userGroupTransfers;
    }
}
