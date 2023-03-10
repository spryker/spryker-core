<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclMerchantPortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclMerchantPortal
 * @group Business
 * @group Facade
 * @group ExpandAclEntityConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandAclEntityConfigurationTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AclMerchantPortal\AclMerchantPortalBusinessTester
     */
    protected AclMerchantPortalBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandAclEntityConfigurationExecutesAclEntityConfigurationExpanderPlugins(): void
    {
        // Assert
        $aclEntityConfigurationExpanderPluginMock = $this
            ->getMockBuilder(AclEntityConfigurationExpanderPluginInterface::class)
            ->getMock();

        $aclEntityConfigurationExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturnCallback(function (AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer) {
                return $aclEntityMetadataConfigTransfer;
            });

        // Arrange
        $this->tester->setDependency(
            AclMerchantPortalDependencyProvider::PLUGINS_ACL_ENTITY_CONFIGURATION_EXPANDER,
            [$aclEntityConfigurationExpanderPluginMock],
        );

        // Act
        $this->tester->getFacade()->expandAclEntityConfiguration(new AclEntityMetadataConfigTransfer());
    }

    /**
     * @return void
     */
    public function testExpandAclEntityConfigurationDefinesEmptyAclEntityMetadataCollection(): void
    {
        // Act
        $aclEntityMetadataConfigTransfer = $this->tester
            ->getFacade()
            ->expandAclEntityConfiguration(new AclEntityMetadataConfigTransfer());

        // Assert
        $this->assertNotEmpty($aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection());
    }
}
