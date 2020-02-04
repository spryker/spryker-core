<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantUserConfig extends AbstractBundleConfig
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     */
    public const USER_CREATION_DEFAULT_STATUS = 'blocked';
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::VALIDATOR_WILDCARD
     */
    protected const VALIDATOR_WILDCARD = '*';
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ALLOW
     */
    protected const ALLOW = 'allow';
    protected const MERCHANT_PORTAL_ADMIN_ROLE = 'merchant_portal_admin_role';
    protected const MERCHANT_PORTAL_ADMIN_GROUP = 'merchant_portal_admin_group';
    protected const MERCHANT_PORTAL_ADMIN_GROUP_REFERENCE = 'merchant_portal_admin_group_reference';

    /**
     * @return string
     */
    public function getUserCreationStatus(): string
    {
        return static::USER_CREATION_DEFAULT_STATUS;
    }

    /**
     * @return string
     */
    public function getMerchantAdminGroupReference(): string
    {
        return static::MERCHANT_PORTAL_ADMIN_GROUP_REFERENCE;
    }

    /**
     * @return bool
     */
    public function canUserHaveManyMerchants(): bool
    {
        return false;
    }

    /**
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getInstallRoles(): array
    {
        return [
            (new RoleTransfer())
                ->setName(static::MERCHANT_PORTAL_ADMIN_ROLE)
                ->setAclGroup(
                    (new GroupTransfer())->setName(static::MERCHANT_PORTAL_ADMIN_GROUP)
                )
                ->addAclRule(
                    (new RuleTransfer())
                        ->setBundle(static::VALIDATOR_WILDCARD)
                        ->setController(static::VALIDATOR_WILDCARD)
                        ->setAction(static::VALIDATOR_WILDCARD)
                        ->setType(static::ALLOW)
                ),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getInstallGroups(): array
    {
        return [
            (new GroupTransfer())
                ->setName(static::MERCHANT_PORTAL_ADMIN_GROUP)
                ->setReference(static::MERCHANT_PORTAL_ADMIN_GROUP_REFERENCE),
        ];
    }
}
