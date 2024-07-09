<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui\Communication\Plugin\Security\Handler;

use Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler\UserAuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
 * @group UserAuthenticationFailureHandlerTest
 * Add your own group annotations below this line
 */
class UserAuthenticationFailureHandlerTest extends AbstractHandlerTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationFailureAddsFailedLoginAuditLog(): void
    {
        // Arrange
        $userAuthenticationFailureHandler = $this->getUserAuthenticationFailureHandler('Failed Login');

        // Act
        $userAuthenticationFailureHandler->onAuthenticationFailure(new Request(), new AuthenticationException());
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler\UserAuthenticationFailureHandler|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getUserAuthenticationFailureHandler(string $expectedAuditLogMessage): UserAuthenticationFailureHandler
    {
        $securityGuiCommunicationFactoryMock = $this->getSecurityGuiCommunicationFactoryMock($expectedAuditLogMessage);
        $userAuthenticationFailureHandler = new UserAuthenticationFailureHandler();
        $userAuthenticationFailureHandler->setFactory($securityGuiCommunicationFactoryMock);

        return $userAuthenticationFailureHandler;
    }
}
