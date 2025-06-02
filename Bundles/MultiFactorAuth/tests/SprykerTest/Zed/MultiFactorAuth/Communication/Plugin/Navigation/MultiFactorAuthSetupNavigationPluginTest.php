<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\MultiFactorAuth\Communication\Plugin\Navigation;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LinkTransfer;
use ReflectionClass;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface;
use Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory;
use Spryker\Zed\MultiFactorAuth\Communication\Plugin\Navigation\MultiFactorAuthSetupNavigationPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Communication
 * @group Plugin
 * @group Navigation
 * @group MultiFactorAuthSetupNavigationPluginTest
 * Add your own group annotations below this line
 */
class MultiFactorAuthSetupNavigationPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const URL_MULTI_FACTOR_AUTH_USER_MANAGEMENT_SET_UP = '/multi-factor-auth/user-management/set-up';

    /**
     * @var string
     */
    protected const LABEL_SET_UP_MULTI_FACTOR_AUTHENTICATION = 'Set up Multi-Factor Authentication';

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Communication\Plugin\Navigation\MultiFactorAuthSetupNavigationPlugin
     */
    protected MultiFactorAuthSetupNavigationPlugin $multiFactorAuthSetupNavigationPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory
     */
    protected $factoryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->factoryMock = $this->getMockBuilder(MultiFactorAuthCommunicationFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->multiFactorAuthSetupNavigationPlugin = new MultiFactorAuthSetupNavigationPlugin();
        $multiFactorAuthSetupNavigationPluginReflection = new ReflectionClass(MultiFactorAuthSetupNavigationPlugin::class);

        $factoryProperty = $multiFactorAuthSetupNavigationPluginReflection->getProperty('factory');
        $factoryProperty->setValue($this->multiFactorAuthSetupNavigationPlugin, $this->factoryMock);
    }

    /**
     * @return void
     */
    public function testGetNavigationItemsReturnsNullWhenNoPluginsAvailable(): void
    {
        // Arrange
        $this->factoryMock->method('getUserMultiFactorAuthPlugins')
            ->willReturn([]);

        // Act
        $result = $this->multiFactorAuthSetupNavigationPlugin->getNavigationItem();

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testGetNavigationItemsReturnsLinkTransferWhenPluginsAvailable(): void
    {
        // Arrange
        $mockPlugin = $this->createMock(MultiFactorAuthPluginInterface::class);

        $this->factoryMock->method('getUserMultiFactorAuthPlugins')
            ->willReturn([$mockPlugin]);

        // Act
        $result = $this->multiFactorAuthSetupNavigationPlugin->getNavigationItem();

        // Assert
        $this->assertInstanceOf(LinkTransfer::class, $result);
        $this->assertSame(static::URL_MULTI_FACTOR_AUTH_USER_MANAGEMENT_SET_UP, $result->getUrl());
        $this->assertSame(static::LABEL_SET_UP_MULTI_FACTOR_AUTHENTICATION, $result->getLabel());
    }
}
