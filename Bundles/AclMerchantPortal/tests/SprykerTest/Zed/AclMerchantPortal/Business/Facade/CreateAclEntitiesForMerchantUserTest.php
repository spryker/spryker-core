<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider;
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
 * @group CreateAclEntitiesForMerchantUserTest
 * Add your own group annotations below this line
 */
class CreateAclEntitiesForMerchantUserTest extends Unit
{
    /**
     * @uses {@link \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig::ACL_ROLE_PRODUCT_VIEWER_REFERENCE}
     *
     * @var string
     */
    protected const ACL_ROLE_PRODUCT_VIEWER_REFERENCE = 'product-viewer-for-offer-creation';

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
     * @dataProvider getMandatoryPropertyDataProvider
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserFailsWithoutRequiredProperties(MerchantUserTransfer $merchantUserTransfer): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($merchantUserTransfer);
    }

    /**
     * @return array<string, \Generated\Shared\Transfer\MerchantUserTransfer>
     */
    public function getMandatoryPropertyDataProvider(): array
    {
        return [
            'requires `id_merchant_user` to be set' => [
                (new MerchantUserTransfer())
                    ->setIdMerchantUser(null)
                    ->setUser((new UserBuilder())->build()->setIdUser(1234))
                    ->setMerchant((new MerchantBuilder())->build()),
            ],
            'requires `id_user` to be set' => [
                (new MerchantUserTransfer())
                    ->setIdMerchantUser(1234)
                    ->setUser((new UserBuilder())->build()->setIdUser(null))
                    ->setMerchant((new MerchantBuilder())->build()),
            ],
            'requires `first_name` to be set' => [
                (new MerchantUserTransfer())
                    ->setIdMerchantUser(1234)
                    ->setUser((new UserBuilder())->build()->setIdUser(1234)->setFirstName(null))
                    ->setMerchant((new MerchantBuilder())->build()),
            ],
            'requires `last_name` to be set' => [
                (new MerchantUserTransfer())
                    ->setIdMerchantUser(1234)
                    ->setUser((new UserBuilder())->build()->setIdUser(1234)->setLastName(null))
                    ->setMerchant((new MerchantBuilder())->build())],
            'requires `name` to be set' => [
                (new MerchantUserTransfer())
                    ->setIdMerchantUser(1234)
                    ->setUser((new UserBuilder())->build()->setIdUser(1234))
                    ->setMerchant((new MerchantBuilder())->build()->setName(null)),
            ],
        ];
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserFailsWithoutRequiredMerchantReference(): void
    {
        // Arrange
        $merchantUserTransfer = $this->tester->createMerchantUser();
        $merchantUserTransfer->getMerchant()->setMerchantReference(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($merchantUserTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserExecutesMerchantUserAclRuleExpanderPlugins(): void
    {
        // Assert
        $merchantUserAclRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantUserAclRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantUserAclRuleExpanderPluginMock
            ->expects($this->once())
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
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserExecutesMerchantUserAclEntityRuleExpanderPlugins(): void
    {
        // Assert
        $merchantUserAclEntityRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantUserAclEntityRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantUserAclEntityRuleExpanderPluginMock
            ->expects($this->once())
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
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserCreatesSegment(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclEntitySegmentPropelQuery()->count());

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());

        // Assert
        $this->assertSame(1, $this->tester->getAclEntitySegmentPropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserCreatesRole(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclRolePropelQuery()->count());

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());

        // Assert
        $this->assertSame(1, $this->tester->getAclRolePropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserCreatesRules(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclRulePropelQuery()->count());

        $merchantUserAclRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantUserAclRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantUserAclRuleExpanderPluginMock
            ->method('expand')
            ->willReturnCallback(function (array $ruleTransfers) {
                $ruleTransfers[] = (new RuleTransfer())
                    ->setBundle('security-merchant-portal-gui')
                    ->setController('*')
                    ->setAction('*')
                    ->setType('allow');

                return $ruleTransfers;
            });

        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_USER_ACL_RULE_EXPANDER,
            [$merchantUserAclRuleExpanderPluginMock],
        );

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());

        // Assert
        $this->assertSame(1, $this->tester->getAclRulePropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserCreatesEntityRules(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclEntityRulePropelQuery()->count());

        $merchantUserAclEntityRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantUserAclEntityRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantUserAclEntityRuleExpanderPluginMock
            ->method('expand')
            ->willReturnCallback(function (array $aclEntityRuleTransfers) {
                $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
                    ->setEntity('Orm\Zed\MerchantUser\Persistence\SpyMerchantUser')
                    ->setScope('segment')
                    ->setPermissionMask(0b1 | 0b100);

                return $aclEntityRuleTransfers;
            });

        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_MERCHANT_USER_ACL_ENTITY_RULE_EXPANDER,
            [$merchantUserAclEntityRuleExpanderPluginMock],
        );

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());

        // Assert
        $this->assertSame(1, $this->tester->getAclEntityRulePropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserCreatesGroup(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclGroupPropelQuery()->count());

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());

        // Assert
        $this->assertSame(1, $this->tester->getAclGroupPropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserAddsMerchantUserToMerchantUserGroup(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclUserHasGroupQuery()->count());

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());

        // Assert
        $this->assertSame(1, $this->tester->getAclUserHasGroupQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserAddsMerchantUserToProductViewerGroup(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclUserHasGroupQuery()->count());
        $this->tester->haveGroup([GroupTransfer::REFERENCE => static::ACL_ROLE_PRODUCT_VIEWER_REFERENCE]);

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($this->tester->createMerchantUser());

        // Assert
        $this->assertSame(2, $this->tester->getAclUserHasGroupQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantUserAddsMerchantUserToMerchantGroup(): void
    {
        // Arrange
        $merchantUserTransfer = $this->tester->createMerchantUser();
        $this->assertSame(0, $this->tester->getAclUserHasGroupQuery()->count());

        $this->tester->haveGroup([
            GroupTransfer::REFERENCE => sprintf('%s%s', '__MERCHANT_', $merchantUserTransfer->getMerchant()->getMerchantReference()),
        ]);

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchantUser($merchantUserTransfer);

        // Assert
        $this->assertSame(2, $this->tester->getAclUserHasGroupQuery()->count());
    }
}
