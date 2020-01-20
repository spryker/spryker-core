<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantUserConfig extends AbstractBundleConfig
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     */
    public const USER_CREATION_DEFAULT_STATUS = 'blocked';
    public const USER_CREATION_DEFAULT_PASSWORD_LENGTH = 8;
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::VALIDATOR_WILDCARD
     */
    public const VALIDATOR_WILDCARD = '*';
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ALLOW
     */
    public const ALLOW = 'allow';
    public const MERCHANT_PORTAL_ADMIN_ROLE = 'merchant_portal_admin_role';
    public const MERCHANT_PORTAL_ADMIN_GROUP = 'merchant_portal_admin_group';
    public const MERCHANT_PORTAL_ADMIN_GROUP_REFERENCE = 'merchant_portal_admin_group_reference';

    /**
     * @return bool
     */
    public function canUserHaveManyMerchants(): bool
    {
        return false;
    }

    /**
     * @return array
     */
    public function getInstallRoles(): array
    {
        return [
            [
                'name' => static::MERCHANT_PORTAL_ADMIN_ROLE,
                'group' => [
                    'name' => static::MERCHANT_PORTAL_ADMIN_GROUP,
                ],
                'rule' => [
                    'bundle' => static::VALIDATOR_WILDCARD,
                    'controller' => static::VALIDATOR_WILDCARD,
                    'action' => static::VALIDATOR_WILDCARD,
                    'type' => static::ALLOW,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getInstallGroups(): array
    {
        return [
            [
                'name' => static::MERCHANT_PORTAL_ADMIN_GROUP,
                'reference' => static::MERCHANT_PORTAL_ADMIN_GROUP_REFERENCE,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getInstallUserGroups(): array
    {
        return [];
    }
}
