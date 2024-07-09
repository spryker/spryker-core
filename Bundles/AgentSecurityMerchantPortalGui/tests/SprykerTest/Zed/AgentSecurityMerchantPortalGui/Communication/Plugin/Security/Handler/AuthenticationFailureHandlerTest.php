<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler\AuthenticationFailureHandler;
use SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\AbstractPluginTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentSecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group Handler
 * @group AuthenticationFailureHandlerTest
 * Add your own group annotations below this line
 */
class AuthenticationFailureHandlerTest extends AbstractPluginTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationFailureAddsAgentFailedLoginAuditLog(): void
    {
        // Arrange
        $authenticationFailureHandler = $this->getAuthenticationFailureHandler('Failed Login (Agent)');

        // Act
        $authenticationFailureHandler->onAuthenticationFailure(new Request(), new AuthenticationException());
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler\AuthenticationFailureHandler
     */
    protected function getAuthenticationFailureHandler(string $expectedMessage): AuthenticationFailureHandler
    {
        $agentSecurityMerchantPortalGuiCommunicationFactoryMock = $this->getAgentSecurityMerchantPortalGuiCommunicationFactoryMock($expectedMessage);
        $authenticationFailureHandler = new AuthenticationFailureHandler();
        $authenticationFailureHandler->setFactory($agentSecurityMerchantPortalGuiCommunicationFactoryMock);

        return $authenticationFailureHandler;
    }
}
