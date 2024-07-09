<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler\AuthenticationSuccessHandler;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToUserFacadeBridge;
use SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\AbstractPluginTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

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
 * @group AuthenticationSuccessHandlerTest
 * Add your own group annotations below this line
 */
class AuthenticationSuccessHandlerTest extends AbstractPluginTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationSuccessAddsAgentSuccessfulLoginAuditLog(): void
    {
        // Arrange
        $authenticationSuccessHandler = $this->getAuthenticationSuccessHandler('Successful Login (Agent)');
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        // Act
        $authenticationSuccessHandler->onAuthenticationSuccess($request, $this->getPostAuthenticationTokenMock());
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler\AuthenticationSuccessHandler
     */
    protected function getAuthenticationSuccessHandler(string $expectedMessage): AuthenticationSuccessHandler
    {
        $agentSecurityMerchantPortalGuiCommunicationFactoryMock = $this->getAgentSecurityMerchantPortalGuiCommunicationFactoryMock($expectedMessage);
        $agentSecurityMerchantPortalGuiCommunicationFactoryMock->method('getUserFacade')->willReturn(
            $this->getMockBuilder(AgentSecurityMerchantPortalGuiToUserFacadeBridge::class)
                ->disableOriginalConstructor()
                ->getMock(),
        );
        $authenticationSuccessHandler = new AuthenticationSuccessHandler();
        $authenticationSuccessHandler->setFactory($agentSecurityMerchantPortalGuiCommunicationFactoryMock);

        return $authenticationSuccessHandler;
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPostAuthenticationTokenMock(): PostAuthenticationToken
    {
        $postAuthenticationTokenMock = $this->getMockBuilder(PostAuthenticationToken::class)
            ->disableOriginalConstructor()
            ->getMock();
        $postAuthenticationTokenMock->method('getUser')->willReturn(new AgentMerchantUser(
            (new UserTransfer())->setUsername('test')->setPassword('test'),
        ));

        return $postAuthenticationTokenMock;
    }
}
