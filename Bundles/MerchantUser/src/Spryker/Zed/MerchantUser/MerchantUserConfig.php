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
    protected const RULE_VALIDATOR_WILDCARD = '*';
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ALLOW
     */
    protected const RULE_TYPE_ALLOW = 'allow';
    protected const MERCHANT_ADMIN_ROLE_NAME = 'Merchant Admin';
    protected const MERCHANT_ADMIN_GROUP_NAME = 'Merchant Admin';
    protected const MERCHANT_ADMIN_GROUP_REFERENCE = 'merchant_admin';

    /**
     * @api
     *
     * @return string
     */
    public function getUserCreationStatus(): string
    {
        return static::USER_CREATION_DEFAULT_STATUS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantAdminGroupReference(): string
    {
        return static::MERCHANT_ADMIN_GROUP_REFERENCE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantAdminRoleName(): string
    {
        return static::MERCHANT_ADMIN_ROLE_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantAdminGroupName(): string
    {
        return static::MERCHANT_ADMIN_GROUP_NAME;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function canUserHaveManyMerchants(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function getInstallAclRoles(): array
    {
        return [
            $this->getMerchantAdminAclRole(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    protected function getMerchantAdminAclRole(): RoleTransfer
    {
        return (new RoleTransfer())
            ->setName($this->getMerchantAdminRoleName())
            ->setAclGroup((new GroupTransfer())->setName($this->getMerchantAdminGroupName()))
            ->setAclRules(new ArrayObject($this->getMerchantAdminAclRules()));
    }

    /**
     * @return array
     */
    protected function getMerchantAdminAclRules(): array
    {
        return [
            (new RuleTransfer())
                ->setBundle(static::RULE_VALIDATOR_WILDCARD)
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
        ];
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function getInstallAclGroups(): array
    {
        return [
            (new GroupTransfer())
                ->setName($this->getMerchantAdminGroupName())
                ->setReference($this->getMerchantAdminGroupReference()),
        ];
    }
}
