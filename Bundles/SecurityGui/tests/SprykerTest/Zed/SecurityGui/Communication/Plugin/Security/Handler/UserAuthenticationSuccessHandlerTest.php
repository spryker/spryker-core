<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui\Communication\Plugin\Security\Handler;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityGui\Business\SecurityGuiFacade;
use Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler\UserAuthenticationSuccessHandler;
use Spryker\Zed\SecurityGui\Communication\Security\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group Handler
 * @group UserAuthenticationSuccessHandlerTest
 * Add your own group annotations below this line
 */
class UserAuthenticationSuccessHandlerTest extends AbstractHandlerTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationSuccessAddsSuccessfulLoginAuditLog(): void
    {
        // Arrange
        $userAuthenticationSuccessHandler = $this->getUserAuthenticationSuccessHandler('Successful Login');
        $tokenMock = $this->getPostAuthenticationTokenMock();
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        // Act
        $userAuthenticationSuccessHandler->onAuthenticationSuccess($request, $tokenMock);
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler\UserAuthenticationSuccessHandler
     */
    protected function getUserAuthenticationSuccessHandler(string $expectedAuditLogMessage): UserAuthenticationSuccessHandler
    {
        $securityGuiCommunicationFactoryMock = $this->getSecurityGuiCommunicationFactoryMock($expectedAuditLogMessage);
        $userAuthenticationSuccessHandler = new UserAuthenticationSuccessHandler();
        $userAuthenticationSuccessHandler->setFactory($securityGuiCommunicationFactoryMock);
        $userAuthenticationSuccessHandler->setFacade($this->getSecurityGuiFacadeMock());

        return $userAuthenticationSuccessHandler;
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPostAuthenticationTokenMock(): PostAuthenticationToken
    {
        $postAuthenticationTokenMock = $this->getMockBuilder(PostAuthenticationToken::class)
            ->disableOriginalConstructor()
            ->getMock();
        $postAuthenticationTokenMock->method('getUser')->willReturn(new User(
            (new UserTransfer())->setUsername('test')->setPassword('test'),
        ));

        return $postAuthenticationTokenMock;
    }

    /**
     * @return \Spryker\Zed\SecurityGui\Business\SecurityGuiFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSecurityGuiFacadeMock(): SecurityGuiFacade
    {
        return $this->createMock(SecurityGuiFacade::class);
    }
}
