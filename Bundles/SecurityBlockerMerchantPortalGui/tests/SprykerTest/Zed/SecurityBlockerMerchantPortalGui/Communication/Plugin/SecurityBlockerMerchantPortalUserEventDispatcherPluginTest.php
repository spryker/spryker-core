<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityBlockerMerchantPortalGui\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Plugin\EventDispatcher\SecurityBlockerMerchantPortalUserEventDispatcherPlugin;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\SecurityBlockerMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiDependencyProvider;
use SprykerTest\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityBlockerMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group SecurityBlockerMerchantPortalUserEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class SecurityBlockerMerchantPortalUserEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SecurityBlockerMerchantPortalGui\SecurityBlockerMerchantPortalGuiCommunicationTester
     */
    protected SecurityBlockerMerchantPortalGuiCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Plugin\EventDispatcher\SecurityBlockerMerchantPortalUserEventDispatcherPlugin
     */
    protected SecurityBlockerMerchantPortalUserEventDispatcherPlugin $securityBlockerMerchantPortalUserEventDispatcherPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $securityBlockerMerchantPortalGuiDependencyProvider = new SecurityBlockerMerchantPortalGuiDependencyProvider();
        $securityBlockerMerchantPortalGuiDependencyProvider->provideCommunicationLayerDependencies($container);

        $securityBlockerMerchantPortalGuiFactoryMock = $this->createMock(SecurityBlockerMerchantPortalGuiCommunicationFactory::class);
        $securityBlockerMerchantPortalGuiFactoryMock->setContainer($container);

        $this->securityBlockerMerchantPortalUserEventDispatcherPlugin = new SecurityBlockerMerchantPortalUserEventDispatcherPlugin();
        $this->securityBlockerMerchantPortalUserEventDispatcherPlugin->setFactory($securityBlockerMerchantPortalGuiFactoryMock);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerMerchantPortalUserEventDispatcherPluginShouldCallAddSubscriberOnEventDispatcher(): void
    {
        // Arrange
        $eventDispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();

        // Assert
        $eventDispatcherMock->expects($this->once())
            ->method('addSubscriber');

        // Act
        $this->securityBlockerMerchantPortalUserEventDispatcherPlugin->extend(
            $eventDispatcherMock,
            $this->createMock(ContainerInterface::class),
        );
    }
}
