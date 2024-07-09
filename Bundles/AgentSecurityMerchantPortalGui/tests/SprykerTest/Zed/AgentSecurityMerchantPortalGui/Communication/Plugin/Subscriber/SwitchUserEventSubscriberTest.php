<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Subscriber;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Subscriber\SwitchUserEventSubscriber;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser;
use SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\AbstractPluginTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentSecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Subscriber
 * @group SwitchUserEventSubscriberTest
 * Add your own group annotations below this line
 */
class SwitchUserEventSubscriberTest extends AbstractPluginTest
{
    /**
     * @return void
     */
    public function testSwitchUserAddsImpersonationStartedAuditLog(): void
    {
        // Arrange
        $switchUserEventSubscriber = $this->getSwitchUserEventSubscriber('Impersonation Started');
        $switchUserEvent = new SwitchUserEvent(new Request(), new MerchantUser(
            (new MerchantUserTransfer())->setUser((new UserTransfer())->setUsername('test')),
        ));

        // Act
        $switchUserEventSubscriber->switchUser($switchUserEvent);
    }

    /**
     * @return void
     */
    public function testSwitchUserAddsImpersonationEndedAuditLog(): void
    {
        // Arrange
        $switchUserEventSubscriber = $this->getSwitchUserEventSubscriber('Impersonation Ended');
        $switchUserEvent = new SwitchUserEvent(new Request(), new AgentMerchantUser(
            (new UserTransfer())->setUsername('test')->setPassword('test'),
        ));

        // Act
        $switchUserEventSubscriber->switchUser($switchUserEvent);
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Subscriber\SwitchUserEventSubscriber
     */
    protected function getSwitchUserEventSubscriber(string $expectedMessage): SwitchUserEventSubscriber
    {
        $agentSecurityMerchantPortalGuiCommunicationFactoryMock = $this->getAgentSecurityMerchantPortalGuiCommunicationFactoryMock($expectedMessage);
        $switchUserEventSubscriber = new SwitchUserEventSubscriber();
        $switchUserEventSubscriber->setFactory($agentSecurityMerchantPortalGuiCommunicationFactoryMock);

        return $switchUserEventSubscriber;
    }
}
