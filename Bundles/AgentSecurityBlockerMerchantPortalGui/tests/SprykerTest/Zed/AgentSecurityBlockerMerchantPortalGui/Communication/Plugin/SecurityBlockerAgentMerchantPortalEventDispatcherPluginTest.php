<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiDependencyProvider;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\AgentSecurityBlockerMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\Plugin\EventDispatcher\SecurityBlockerAgentMerchantPortalEventDispatcherPlugin;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentSecurityBlockerMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group SecurityBlockerAgentMerchantPortalEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class SecurityBlockerAgentMerchantPortalEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AgentSecurityBlockerMerchantPortalGui\AgentSecurityBlockerMerchantPortalGuiCommunicationTester
     */
    protected AgentSecurityBlockerMerchantPortalGuiCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\Plugin\EventDispatcher\SecurityBlockerAgentMerchantPortalEventDispatcherPlugin
     */
    protected SecurityBlockerAgentMerchantPortalEventDispatcherPlugin $securityBlockerAgentMerchantPortalEventDispatcherPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $agentSecurityBlockerMerchantPortalGuiDependencyProvider = new AgentSecurityBlockerMerchantPortalGuiDependencyProvider();
        $agentSecurityBlockerMerchantPortalGuiDependencyProvider->provideCommunicationLayerDependencies($container);

        $agentSecurityBlockerMerchantPortalGuiFactoryMock = $this->createMock(AgentSecurityBlockerMerchantPortalGuiCommunicationFactory::class);
        $agentSecurityBlockerMerchantPortalGuiFactoryMock->setContainer($container);

        $this->securityBlockerAgentMerchantPortalEventDispatcherPlugin = new SecurityBlockerAgentMerchantPortalEventDispatcherPlugin();
        $this->securityBlockerAgentMerchantPortalEventDispatcherPlugin->setFactory($agentSecurityBlockerMerchantPortalGuiFactoryMock);
    }

    /**
     * @return void
     */
    public function testSecurityBlockerAgentMerchantPortalEventDispatcherPluginShouldCallAddSubscriberOnEventDispatcher(): void
    {
        // Arrange
        $eventDispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();

        // Assert
        $eventDispatcherMock->expects($this->once())
            ->method('addSubscriber');

        // Act
        $this->securityBlockerAgentMerchantPortalEventDispatcherPlugin->extend(
            $eventDispatcherMock,
            $this->createMock(ContainerInterface::class),
        );
    }
}
