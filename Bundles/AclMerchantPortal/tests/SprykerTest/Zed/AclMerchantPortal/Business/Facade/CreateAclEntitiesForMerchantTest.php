<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface;
use SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclMerchantPortal
 * @group Business
 * @group Facade
 * @group CreateAclEntitiesForMerchantTest
 * Add your own group annotations below this line
 */
class CreateAclEntitiesForMerchantTest extends Unit
{
    /**
     * @uses {@link \Spryker\Zed\AclMerchantPortal\Business\Creator\AclEntityCreator::ERROR_MESSAGE_MERCHANT_REFERENCE}
     *
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_REFERENCE = 'Merchant reference not found';

    /**
     * @uses {@link \Spryker\Zed\AclMerchantPortal\Business\Creator\AclEntityCreator::ERROR_MESSAGE_MERCHANT_NAME}
     *
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_NAME = 'Merchant name not found';

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
    public function testCreateAclEntitiesForMerchantFailsWithoutRequiredMerchantReference(): void
    {
        // Arrange
        $merchantTransfer = (new MerchantBuilder())->build()->setMerchantReference(null);

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createAclEntitiesForMerchant($merchantTransfer);

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::ERROR_MESSAGE_MERCHANT_REFERENCE,
            $merchantResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantFailsWithoutRequiredMerchantName(): void
    {
        // Arrange
        $merchantTransfer = (new MerchantBuilder())->build()->setName(null);

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createAclEntitiesForMerchant($merchantTransfer);

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
        $this->assertSame(
            static::ERROR_MESSAGE_MERCHANT_NAME,
            $merchantResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantFailsWithoutRequiredIdMerchant(): void
    {
        // Arrange
        $merchantTransfer = (new MerchantBuilder())->build();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantExecutesMerchantAclRuleExpanderPlugins(): void
    {
        // Assert
        $merchantAclRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantAclRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantAclRuleExpanderPluginMock
            ->expects($this->once())
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
        $this->tester->getFacade()->createAclEntitiesForMerchant($this->tester->createMerchant());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantExecutesMerchantAclEntityRuleExpanderPlugins(): void
    {
        // Assert
        $merchantAclEntityRuleExpanderPluginMock = $this
            ->getMockBuilder(MerchantAclEntityRuleExpanderPluginInterface::class)
            ->getMock();

        $merchantAclEntityRuleExpanderPluginMock
            ->expects($this->once())
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
        $this->tester->getFacade()->createAclEntitiesForMerchant($this->tester->createMerchant());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantCreatesSegment(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclEntitySegmentPropelQuery()->count());

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchant($this->tester->createMerchant());

        // Assert
        $this->assertSame(1, $this->tester->getAclEntitySegmentPropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantCreatesRole(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclRolePropelQuery()->count());

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchant($this->tester->createMerchant());

        // Assert
        $this->assertSame(1, $this->tester->getAclRolePropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantCreatesRules(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclRulePropelQuery()->count());

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

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchant($this->tester->createMerchant());

        // Assert
        $this->assertSame(1, $this->tester->getAclRulePropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantCreatesEntityRules(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclEntityRulePropelQuery()->count());

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

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchant($this->tester->createMerchant());

        // Assert
        $this->assertSame(1, $this->tester->getAclEntityRulePropelQuery()->count());
    }

    /**
     * @return void
     */
    public function testCreateAclEntitiesForMerchantCreatesGroup(): void
    {
        // Arrange
        $this->assertSame(0, $this->tester->getAclGroupPropelQuery()->count());

        // Act
        $this->tester->getFacade()->createAclEntitiesForMerchant($this->tester->createMerchant());

        // Assert
        $this->assertSame(1, $this->tester->getAclGroupPropelQuery()->count());
    }
}
