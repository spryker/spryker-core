<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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
 * @group MerchantUserAuthenticationFailureHandlerTest
 * Add your own group annotations below this line
 */
class MerchantUserAuthenticationFailureHandlerTest extends AbstractHandlerTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationFailureAddsFailedLoginAuditLog(): void
    {
        // Arrange
        $merchantUserAuthenticationFailureHandler = $this->getMerchantUserAuthenticationFailureHandler('Failed Login');

        // Act
        $merchantUserAuthenticationFailureHandler->onAuthenticationFailure(new Request(), new AuthenticationException());
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationFailureHandler
     */
    protected function getMerchantUserAuthenticationFailureHandler(
        string $expectedAuditLogMessage
    ): MerchantUserAuthenticationFailureHandler {
        $securityMerchantPortalGuiCommunicationFactoryMock = $this->getSecurityMerchantPortalGuiCommunicationFactoryMock($expectedAuditLogMessage);
        $merchantUserAuthenticationFailureHandler = new MerchantUserAuthenticationFailureHandler();
        $merchantUserAuthenticationFailureHandler->setFactory($securityMerchantPortalGuiCommunicationFactoryMock);

        return $merchantUserAuthenticationFailureHandler;
    }
}
