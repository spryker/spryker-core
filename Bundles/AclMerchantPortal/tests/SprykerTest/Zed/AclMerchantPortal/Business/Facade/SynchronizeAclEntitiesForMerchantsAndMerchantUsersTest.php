<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface;
use SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclMerchantPortal
 * @group Business
 * @group Facade
 * @group SynchronizeAclEntitiesForMerchantsAndMerchantUsersTest
 * Add your own group annotations below this line
 */
class SynchronizeAclEntitiesForMerchantsAndMerchantUsersTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester
     */
    protected AclMerchantPortalBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tester->clearAllAclMerchantData();
    }

    /**
     * @return void
     */
    public function testSynchronizeAclEntitiesForMerchantsAndMerchantUsersCreatesEntities(): void
    {
        // Arrange
        $this->tester->createMerchantUser();
        $this->assertSame(0, $this->tester->getAclEntitySegmentPropelQuery()->count());
        $this->assertSame(0, $this->tester->getAclRolePropelQuery()->count());
        $this->assertSame(0, $this->tester->getAclRulePropelQuery()->count());
        $this->assertSame(0, $this->tester->getAclEntityRulePropelQuery()->count());
        $this->assertSame(0, $this->tester->getAclGroupPropelQuery()->count());

        $this->mockMerchantAclRuleExpanderPlugin();
        $this->merchantAclEntityRuleExpanderPluginMock();

        // Act
        $this->tester->getFacade()->synchronizeAclEntitiesForMerchantsAndMerchantUsers();

        // Assert
        $this->assertGreaterThan(1, $this->tester->getAclEntitySegmentPropelQuery()->count());
        $this->assertGreaterThan(1, $this->tester->getAclRolePropelQuery()->count());
        $this->assertGreaterThan(1, $this->tester->getAclRulePropelQuery()->count());
        $this->assertGreaterThan(1, $this->tester->getAclEntityRulePropelQuery()->count());
        $this->assertGreaterThan(1, $this->tester->getAclGroupPropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testSynchronizeAclEntitiesForMerchantsAndMerchantUsersExecutesMerchantAclRuleExpanderPlugins(): void
    {
        // Assert
        $merchantAclRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantAclRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantAclRuleExpanderPluginMock
            ->expects($this->atLeastOnce())
            ->method('expand')
            ->willReturnCallback(function (array $ruleTransfers) {
                return $ruleTransfers;
            });

        // Arrange
        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_ACL_RULE_EXPANDER,
            [$merchantAclRuleExpanderPluginMock],
        );

        // Act
        $this->tester->getFacade()->synchronizeAclEntitiesForMerchantsAndMerchantUsers();
    }

    /**
     * @return void
     */
    public function testSynchronizeAclEntitiesForMerchantsAndMerchantUsersExecutesMerchantAclEntityRuleExpanderPlugins(): void
    {
        // Assert
        $merchantAclEntityRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantAclEntityRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantAclEntityRuleExpanderPluginMock
            ->expects($this->atLeastOnce())
            ->method('expand')
            ->willReturnCallback(function (array $aclEntityRuleTransfers) {
                return $aclEntityRuleTransfers;
            });

        // Arrange
        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_ACL_ENTITY_RULE_EXPANDER,
            [$merchantAclEntityRuleExpanderPluginMock],
        );

        // Act
        $this->tester->getFacade()->synchronizeAclEntitiesForMerchantsAndMerchantUsers();
    }

    /**
     * @return void
     */
    public function testSynchronizeAclEntitiesForMerchantsAndMerchantUsersExecutesMerchantUserAclRuleExpanderPlugins(): void
    {
        // Assert
        $merchantUserAclRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantUserAclRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantUserAclRuleExpanderPluginMock
            ->expects($this->atLeastOnce())
            ->method('expand')
            ->willReturnCallback(function (array $ruleTransfers) {
                return $ruleTransfers;
            });

        // Arrange
        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_USER_ACL_RULE_EXPANDER,
            [$merchantUserAclRuleExpanderPluginMock],
        );

        // Act
        $this->tester->getFacade()->synchronizeAclEntitiesForMerchantsAndMerchantUsers();
    }

    /**
     * @return void
     */
    public function testSynchronizeAclEntitiesForMerchantsAndMerchantUsersExecutesMerchantUserAclEntityRuleExpanderPlugins(): void
    {
        // Assert
        $merchantUserAclEntityRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantUserAclEntityRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantUserAclEntityRuleExpanderPluginMock
            ->expects($this->atLeastOnce())
            ->method('expand')
            ->willReturnCallback(function (array $aclEntityRuleTransfers) {
                return $aclEntityRuleTransfers;
            });

        // Arrange
        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_USER_ACL_ENTITY_RULE_EXPANDER,
            [$merchantUserAclEntityRuleExpanderPluginMock],
        );

        // Act
        $this->tester->getFacade()->synchronizeAclEntitiesForMerchantsAndMerchantUsers();
    }

    /**
     * @return void
     */
    protected function mockMerchantAclRuleExpanderPlugin(): void
    {
        $merchantAclRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantAclRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantAclRuleExpanderPluginMock
            ->method('expand')
            ->willReturnCallback(function (array $ruleTransfers) {
                $ruleTransfers[] = (new RuleTransfer())
                    ->setBundle('dashboard-merchant-portal-gui')
                    ->setController('*')
                    ->setAction('*')
                    ->setType('allow');

                return $ruleTransfers;
            });

        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_ACL_RULE_EXPANDER,
            [$merchantAclRuleExpanderPluginMock],
        );
    }

    /**
     * @return void
     */
    protected function merchantAclEntityRuleExpanderPluginMock(): void
    {
        $merchantAclEntityRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantAclEntityRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantAclEntityRuleExpanderPluginMock
            ->method('expand')
            ->willReturnCallback(function (array $aclEntityRuleTransfers) {
                $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
                    ->setEntity('Orm\Zed\Merchant\Persistence\SpyMerchant')
                    ->setScope('segment')
                    ->setPermissionMask(0b1 | 0b100);

                return $aclEntityRuleTransfers;
            });

        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_ACL_ENTITY_RULE_EXPANDER,
            [$merchantAclEntityRuleExpanderPluginMock],
        );
    }
}
