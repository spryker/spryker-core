<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityBlockerBackofficeGui\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Plugin\EventDispatcher\SecurityBlockerBackofficeUserEventDispatcherPlugin;
use Spryker\Zed\SecurityBlockerBackofficeGui\Communication\SecurityBlockerBackofficeGuiCommunicationFactory;
use Spryker\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiDependencyProvider;
use SprykerTest\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityBlockerBackofficeGui
 * @group Communication
 * @group Plugin
 * @group SecurityBlockerBackOfficeUserEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class SecurityBlockerBackOfficeUserEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiCommunicationTester
     */
    protected SecurityBlockerBackofficeGuiCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Plugin\EventDispatcher\SecurityBlockerBackofficeUserEventDispatcherPlugin
     */
    protected SecurityBlockerBackofficeUserEventDispatcherPlugin $securityBlockerBackOfficeUserEventDispatcherPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $securityBlockerBackofficeGuiDependencyProvider = new SecurityBlockerBackofficeGuiDependencyProvider();
        $securityBlockerBackofficeGuiDependencyProvider->provideCommunicationLayerDependencies($container);

        $securityBlockerBackofficeGuiFactoryMock = $this->createMock(SecurityBlockerBackofficeGuiCommunicationFactory::class);
        $securityBlockerBackofficeGuiFactoryMock->setContainer($container);

        $this->securityBlockerBackOfficeUserEventDispatcherPlugin = new SecurityBlockerBackofficeUserEventDispatcherPlugin();
        $this->securityBlockerBackOfficeUserEventDispatcherPlugin->setFactory($securityBlockerBackofficeGuiFactoryMock);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerBackOfficeUserEventDispatcherPluginShouldCallAddSubscriberOnEventDispatcher(): void
    {
        // Arrange
        $eventDispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();

        // Assert
        $eventDispatcherMock->expects($this->once())
            ->method('addSubscriber');

        // Act
        $this->securityBlockerBackOfficeUserEventDispatcherPlugin->extend(
            $eventDispatcherMock,
            $this->createMock(ContainerInterface::class),
        );
    }
}
