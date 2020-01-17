<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\Acl;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantUser\MerchantUserConfig;

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
        $roles[] = (new RoleTransfer())
            ->setName(MerchantUserConfig::MERCHANT_PORTAL_ADMIN_ROLE)
            ->setGroup(MerchantUserConfig::MERCHANT_PORTAL_ADMIN_GROUP);

        return $roles;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RuleTransfer[]
     */
    public function getRules(): array
    {
        $rules[] = (new RuleTransfer())
            ->setBundle(MerchantUserConfig::VALIDATOR_WILDCARD)
            ->setController(MerchantUserConfig::VALIDATOR_WILDCARD)
            ->setAction(MerchantUserConfig::VALIDATOR_WILDCARD)
            ->setType(MerchantUserConfig::ALLOW)
            ->setRole(MerchantUserConfig::MERCHANT_PORTAL_ADMIN_ROLE);

        return $rules;
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
        $groups[] = (new GroupTransfer())
            ->setName(MerchantUserConfig::MERCHANT_PORTAL_ADMIN_GROUP);

        return $groups;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer[]
     */
    public function getUsers(): array
    {
        return [];
    }
}
