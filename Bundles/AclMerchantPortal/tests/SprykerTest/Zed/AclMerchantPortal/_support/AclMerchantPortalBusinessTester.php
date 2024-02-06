<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer;
use Generated\Shared\Transfer\GroupCriteriaTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Orm\Zed\Acl\Persistence\SpyAclRoleQuery;
use Orm\Zed\Acl\Persistence\SpyAclRuleQuery;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeBridge;

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
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\AclMerchantPortal\PHPMD)
 */
class AclMerchantPortalBusinessTester extends Actor
{
    use _generated\AclMerchantPortalBusinessTesterActions;

    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ROOT_GROUP
     *
     * @var string
     */
    protected const ROOT_GROUP_NAME = 'root_group';

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
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function haveRootGroup(): GroupTransfer
    {
        $groupCriteriaTransfer = (new GroupCriteriaTransfer())->setName(static::ROOT_GROUP_NAME);
        $groupTransfer = $this->getLocator()->acl()->facade()->findGroup($groupCriteriaTransfer);
        if ($groupTransfer) {
            return $groupTransfer;
        }

        return $this->haveGroup([GroupTransfer::NAME => static::ROOT_GROUP_NAME]);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function createMerchant(): MerchantTransfer
    {
        $merchantTransfer = (new MerchantBuilder())->build();

        return $this->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantTransfer::NAME => $merchantTransfer->getName(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function createMerchantUser(): MerchantUserTransfer
    {
        $userTransfer = (new UserBuilder())->build();
        $merchantTransfer = (new MerchantBuilder())->build();

        $userTransfer = $this->haveUser([
            UserTransfer::FIRST_NAME => $userTransfer->getFirstName(),
            UserTransfer::LAST_NAME => $userTransfer->getLastName(),
        ]);

        $merchantTransfer = $this->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantTransfer::NAME => $merchantTransfer->getName(),
        ]);

        $merchantUserTransfer = $this->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantUserTransfer->setUser($userTransfer);

        return $merchantUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer $aclUserHasGroupCollectionTransfer
     *
     * @return void
     */
    public function mockAclFacade(AclUserHasGroupCollectionTransfer $aclUserHasGroupCollectionTransfer): void
    {
        $aclFacadeMock = Stub::makeEmpty(
            AclMerchantPortalToAclFacadeBridge::class,
            [
                'getAclUserHasGroupCollection' => $aclUserHasGroupCollectionTransfer,
            ],
        );
        $this->setDependency(AclMerchantPortalDependencyProvider::FACADE_ACL, $aclFacadeMock);
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRuleQuery
     */
    public function getAclRulePropelQuery(): SpyAclRuleQuery
    {
        return SpyAclRuleQuery::create();
    }

    /**
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntityRuleQuery
     */
    public function getAclEntityRulePropelQuery(): SpyAclEntityRuleQuery
    {
        return SpyAclEntityRuleQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclRoleQuery
     */
    public function getAclRolePropelQuery(): SpyAclRoleQuery
    {
        return SpyAclRoleQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclGroupQuery
     */
    public function getAclGroupPropelQuery(): SpyAclGroupQuery
    {
        return SpyAclGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\AclEntity\Persistence\SpyAclEntitySegmentQuery
     */
    public function getAclEntitySegmentPropelQuery(): SpyAclEntitySegmentQuery
    {
        return SpyAclEntitySegmentQuery::create();
    }

    /**
     * @return \Orm\Zed\Acl\Persistence\SpyAclUserHasGroupQuery
     */
    public function getAclUserHasGroupQuery(): SpyAclUserHasGroupQuery
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
