<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser;

use ArrayObject;
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
    public const VALIDATOR_WILDCARD = '*';
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ALLOW
     */
    public const ALLOW = 'allow';
    public const MERCHANT_PORTAL_ADMIN_ROLE = 'merchant_portal_admin_role';
    public const MERCHANT_PORTAL_ADMIN_GROUP = 'merchant_portal_admin_group';

    /**
     * @return string
     */
    public function getUserCreationStatus(): string
    {
        return static::USER_CREATION_DEFAULT_STATUS;
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
            $this->getMerchantAdminRole(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    protected function getMerchantAdminRole(): RoleTransfer
    {
        return (new RoleTransfer())->setName(static::MERCHANT_PORTAL_ADMIN_ROLE)
            ->setAclGroup($this->getMerchantAdminGroup())
            ->setAclRules($this->getMerchantAdminRules());
    }

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    protected function getMerchantAdminGroup(): GroupTransfer
    {
        return (new GroupTransfer())->setName(static::MERCHANT_PORTAL_ADMIN_GROUP);
    }

    /**
     * @return \ArrayObject
     */
    protected function getMerchantAdminRules(): ArrayObject
    {
        $rules = new ArrayObject();
        $rules[] = (new RuleTransfer())
            ->setBundle(static::VALIDATOR_WILDCARD)
            ->setController(static::VALIDATOR_WILDCARD)
            ->setAction(static::VALIDATOR_WILDCARD)
            ->setType(static::ALLOW);

        return $rules;
    }

    /**
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getInstallGroups(): array
    {
        $groups[] = $this->getMerchantAdminGroup();

        return $groups;
    }
}
