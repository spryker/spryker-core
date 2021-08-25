<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Communication\Plugin\MerchantUser;

use ArrayObject;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface getFacade()
 */
class ProductViewerForOfferCreationAclInstallerPlugin extends AbstractPlugin implements AclInstallerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns ProductViewerForOfferCreation Roles with Rules to create on install.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getRoles(): array
    {
        $groupTransfer = (new GroupTransfer())
            ->setName($this->getConfig()->getProductViewerForOfferCreationAclRoleName())
            ->setReference($this->getConfig()->getProductViewerForOfferCreationAclRoleReference());

        $roleTransfers[] = (new RoleTransfer())
            ->setName($this->getConfig()->getProductViewerForOfferCreationAclRoleName())
            ->setReference($this->getConfig()->getProductViewerForOfferCreationAclRoleReference())
            ->setAclRules(new ArrayObject($this->getConfig()->getProductViewerForOfferCreationAclRoleRules()))
            ->setAclEntityRules(new ArrayObject($this->getConfig()->getProductViewerForOfferCreationAclRoleEntityRules()))
            ->setAclGroup($groupTransfer);

        return $roleTransfers;
    }

    /**
     * {@inheritDoc}
     * - Returns ProductViewerForOfferCreation Groups to create on install.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getGroups(): array
    {
        $groupTransfers[] = (new GroupTransfer())
            ->setName($this->getConfig()->getProductViewerForOfferCreationAclRoleName())
            ->setReference($this->getConfig()->getProductViewerForOfferCreationAclRoleReference());

        return $groupTransfers;
    }
}
