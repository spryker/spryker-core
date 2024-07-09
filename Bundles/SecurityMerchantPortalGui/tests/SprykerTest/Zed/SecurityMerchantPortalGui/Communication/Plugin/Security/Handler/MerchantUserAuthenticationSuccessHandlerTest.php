<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationSuccessHandler;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group Handler
 * @group MerchantUserAuthenticationSuccessHandlerTest
 * Add your own group annotations below this line
 */
class MerchantUserAuthenticationSuccessHandlerTest extends AbstractHandlerTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationSuccessAddsSuccessfulLoginAuditLog(): void
    {
        // Arrange
        $userAuthenticationSuccessHandler = $this->getMerchantUserAuthenticationSuccessHandler('Successful Login');
        $tokenMock = $this->getPostAuthenticationTokenMock();
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        // Act
        $userAuthenticationSuccessHandler->onAuthenticationSuccess($request, $tokenMock);
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationSuccessHandler
     */
    protected function getMerchantUserAuthenticationSuccessHandler(
        string $expectedAuditLogMessage
    ): MerchantUserAuthenticationSuccessHandler {
        $securityMerchantPortalGuiCommunicationFactoryMock = $this->getSecurityMerchantPortalGuiCommunicationFactoryMock($expectedAuditLogMessage);
        $merchantUserAuthenticationSuccessHandler = new MerchantUserAuthenticationSuccessHandler();
        $merchantUserAuthenticationSuccessHandler->setFactory($securityMerchantPortalGuiCommunicationFactoryMock);

        return $merchantUserAuthenticationSuccessHandler;
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPostAuthenticationTokenMock(): PostAuthenticationToken
    {
        $postAuthenticationTokenMock = $this->getMockBuilder(PostAuthenticationToken::class)
            ->disableOriginalConstructor()
            ->getMock();
        $postAuthenticationTokenMock->method('getUser')->willReturn(new MerchantUser(
            (new MerchantUserTransfer())->setUser((new UserTransfer())->setUsername('')),
        ));

        return $postAuthenticationTokenMock;
    }
}
