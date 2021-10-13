<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal;

use Codeception\Actor;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Orm\Zed\Acl\Persistence\SpyAclRuleQuery;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AclMerchantPortalBusinessTester extends Actor
{
    use _generated\AclMerchantPortalBusinessTesterActions;

    /**
     * @return void
     */
    public function assertAclMerchantData(): void
    {
        $ruleTransfers = $this->getZedAclMerchantPortalConfig()->getMerchantAclRoleRules();
        $this->assertSame(count($ruleTransfers), $this->getAclRulePropelQuery()->count());

        $aclEntityRuleTransfers = $this->getZedAclMerchantPortalConfig()->getMerchantAclRoleEntityRules();
        $this->assertSame(count($aclEntityRuleTransfers), $this->getAclEntityRulePropelQuery()->count());

        $this->assertSame(1, $this->getAclRolePropelQuery()->count());
        $this->assertSame(1, $this->getAclGroupPropelQuery()->count());
        $this->assertSame(1, $this->getAclEntitySegmentPropelQuery()->count());
    }

    /**
     * @return void
     */
    public function assertAclMerchantUserData(): void
    {
        $ruleTransfers = $this->getZedAclMerchantPortalConfig()->getMerchantUserAclRoleRules();
        $this->assertSame(count($ruleTransfers), $this->getAclRulePropelQuery()->count());

        $aclEntityRuleTransfers = $this->getZedAclMerchantPortalConfig()->getMerchantUserAclRoleEntityRules();
        $this->assertSame(count($aclEntityRuleTransfers), $this->getAclEntityRulePropelQuery()->count());

        $this->assertSame(1, $this->getAclRolePropelQuery()->count());
        $this->assertSame(1, $this->getAclGroupPropelQuery()->count());
        $this->assertSame(1, $this->getAclEntitySegmentPropelQuery()->count());
        $this->assertSame(1, $this->getAclUserHasGroupQuery()->count());
    }

    /**
     * @return void
     */
    public function clearAllAclMerchantData(): void
    {
        $this->getAclRulePropelQuery()->deleteAll();
        $this->getAclEntityRulePropelQuery()->deleteAll();
        $this->getAclRolePropelQuery()->deleteAll();
        $this->getAclGroupPropelQuery()->deleteAll();
        $this->getAclEntitySegmentPropelQuery()->deleteAll();
        $this->getAclUserHasGroupQuery()->deleteAll();
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfigTransfer(): AclEntityMetadataConfigTransfer
    {
        return (new AclEntityMetadataConfigTransfer())
            ->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    protected function getAclRulePropelQuery(): SpyAclRuleQuery
    {
        return SpyAclRuleQuery::create();
    }

    /**
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery
     */
    protected function getAclEntityRulePropelQuery(): SpyAclEntityRuleQuery
    {
        return SpyAclEntityRuleQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    protected function getAclRolePropelQuery(): SpyAclRoleQuery
    {
        return SpyAclRoleQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    protected function getAclGroupPropelQuery(): SpyAclGroupQuery
    {
        return SpyAclGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery
     */
    protected function getAclEntitySegmentPropelQuery(): SpyAclEntitySegmentQuery
    {
        return SpyAclEntitySegmentQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    protected function getAclUserHasGroupQuery(): SpyAclUserHasGroupQuery
    {
        return SpyAclUserHasGroupQuery::create();
    }

    /**
     * @return \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig
     */
    protected function getZedAclMerchantPortalConfig(): AclMerchantPortalConfig
    {
        return new AclMerchantPortalConfig();
    }
}
