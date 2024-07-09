<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Controller;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Controller\PasswordController;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantResetPasswordForm;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantResetPasswordRequestForm;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Logger\AuditLogger;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityMerchantPortalGui
 * @group Communication
 * @group Controller
 * @group PasswordControllerTest
 * Add your own group annotations below this line
 */
class PasswordControllerTest extends Unit
{
    /**
     * @return void
     */
    public function testResetRequestActionAddsPasswordResetRequestedAuditLog(): void
    {
        // Arrange
        $passwordResetController = $this->getPasswordControllerMock('Password Reset Requested');

        // Act
        $passwordResetController->resetRequestAction(new Request());
    }

    /**
     * @return void
     */
    public function testResetActionAddsPasswordUpdatedAfterResetAuditLog(): void
    {
        // Arrange
        $passwordResetController = $this->getPasswordControllerMock('Password Updated after Reset');

        // Act
        $passwordResetController->resetAction(new Request(['token' => 'token']));
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\Controller\PasswordController|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPasswordControllerMock(string $expectedAuditLogMessage): PasswordController
    {
        $passwordControllerMock = $this->getMockBuilder(PasswordController::class)
            ->onlyMethods([
                'getFactory',
                'isPasswordResetBlocked',
                'incrementPasswordResetBlocker',
                'addSuccessMessage',
                'redirectResponse',
            ])
            ->getMock();
        $passwordControllerMock->method('getFactory')
            ->willReturn($this->getSecurityMerchantPortalGuiCommunicationFactoryMock($expectedAuditLogMessage));
        $passwordControllerMock->method('isPasswordResetBlocked')->willReturn(false);
        $passwordControllerMock->method('addSuccessMessage')->willReturn($passwordControllerMock);
        $passwordControllerMock->method('redirectResponse')->willReturn(new RedirectResponse('/'));

        return $passwordControllerMock;
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSecurityMerchantPortalGuiCommunicationFactoryMock(
        string $expectedAuditLogMessage
    ): SecurityMerchantPortalGuiCommunicationFactory {
        $securityMerchantPortalGuiCommunicationFactoryMock = $this->getMockBuilder(SecurityMerchantPortalGuiCommunicationFactory::class)
            ->getMock();
        $securityMerchantPortalGuiCommunicationFactoryMock->method('createResetPasswordRequestForm')
            ->willReturn($this->getFormMock());
        $securityMerchantPortalGuiCommunicationFactoryMock->method('createResetPasswordForm')
            ->willReturn($this->getFormMock());
        $securityMerchantPortalGuiCommunicationFactoryMock->method('createAuditLogger')
            ->willReturn($this->getAuditLoggerMock($expectedAuditLogMessage));
        $securityMerchantPortalGuiCommunicationFactoryMock->method('getMerchantUserFacade')
            ->willReturn($this->getMerchantUserFacadeMock());
        $securityMerchantPortalGuiCommunicationFactoryMock->method('getConfig')
            ->willReturn($this->createMock(SecurityMerchantPortalGuiConfig::class));

        return $securityMerchantPortalGuiCommunicationFactoryMock;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFormMock(): FormInterface
    {
        $form = $this->getMockBuilder(FormInterface::class)
            ->getMock();
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('handleRequest')->willReturn($form);
        $form->method('getData')->willReturn([
            MerchantResetPasswordRequestForm::FIELD_EMAIL => '',
            MerchantResetPasswordForm::FIELD_PASSWORD => '',
        ]);

        return $form;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getAuditLoggerMock(string $expectedMessage): AuditLogger
    {
        $auditLoggerMock = $this->getMockBuilder(AuditLogger::class)
            ->onlyMethods(['getAuditLogger'])
            ->getMock();
        $auditLoggerMock->expects($this->once())
            ->method('getAuditLogger')
            ->willReturn($this->getLoggerMock($expectedMessage));

        return $auditLoggerMock;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getLoggerMock(string $expectedMessage): LoggerInterface
    {
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $loggerMock->expects($this->once())->method('info')->with($expectedMessage);

        return $loggerMock;
    }

    /**
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Dependency\Facade\SecurityMerchantPortalGuiToMerchantUserFacadeBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMerchantUserFacadeMock(): SecurityMerchantPortalGuiToMerchantUserFacadeBridge
    {
        $merchantUserFacadeMock = $this->getMockBuilder(SecurityMerchantPortalGuiToMerchantUserFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $merchantUserFacadeMock->method('setNewPassword')->willReturn(true);
        $merchantUserFacadeMock->method('isValidPasswordResetToken')->willReturn(true);

        return $merchantUserFacadeMock;
    }
}
