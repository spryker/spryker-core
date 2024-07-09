<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui\Communication\Controller;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Zed\SecurityGui\Communication\Controller\PasswordController;
use Spryker\Zed\SecurityGui\Communication\Form\ResetPasswordForm;
use Spryker\Zed\SecurityGui\Communication\Form\ResetPasswordRequestForm;
use Spryker\Zed\SecurityGui\Communication\Logger\AuditLogger;
use Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserPasswordResetFacadeBridge;
use Spryker\Zed\SecurityGui\SecurityGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityGui
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
     * @return \Spryker\Zed\SecurityGui\Communication\Controller\PasswordController|\PHPUnit\Framework\MockObject\MockObject
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
            ->willReturn($this->getSecurityGuiCommunicationFactoryMock($expectedAuditLogMessage));
        $passwordControllerMock->method('isPasswordResetBlocked')->willReturn(false);
        $passwordControllerMock->method('addSuccessMessage')->willReturn($passwordControllerMock);
        $passwordControllerMock->method('redirectResponse')->willReturn(new RedirectResponse('/'));

        return $passwordControllerMock;
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSecurityGuiCommunicationFactoryMock(string $expectedAuditLogMessage): SecurityGuiCommunicationFactory
    {
        $securityGuiCommunicationFactoryMock = $this->getMockBuilder(SecurityGuiCommunicationFactory::class)
            ->getMock();
        $securityGuiCommunicationFactoryMock->method('createResetPasswordRequestForm')
            ->willReturn($this->getFormMock());
        $securityGuiCommunicationFactoryMock->method('createResetPasswordForm')
            ->willReturn($this->getFormMock());
        $securityGuiCommunicationFactoryMock->method('createAuditLogger')
            ->willReturn($this->getAuditLoggerMock($expectedAuditLogMessage));
        $securityGuiCommunicationFactoryMock->method('getUserPasswordResetFacade')
            ->willReturn($this->getUserPasswordResetFacadeMock());
        $securityGuiCommunicationFactoryMock->method('getConfig')
            ->willReturn($this->createMock(SecurityGuiConfig::class));

        return $securityGuiCommunicationFactoryMock;
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
            ResetPasswordRequestForm::FIELD_EMAIL => '',
            ResetPasswordForm::FIELD_PASSWORD => '',
        ]);

        return $form;
    }

    /**
     * @param string $expectedMessage
     *
     * @return \Spryker\Zed\SecurityGui\Communication\Logger\AuditLogger|\PHPUnit\Framework\MockObject\MockObject
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
     * @return \Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserPasswordResetFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getUserPasswordResetFacadeMock(): SecurityGuiToUserPasswordResetFacadeBridge
    {
        $userPasswordResetFacadeMock = $this->getMockBuilder(SecurityGuiToUserPasswordResetFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userPasswordResetFacadeMock->method('setNewPassword')->willReturn(true);
        $userPasswordResetFacadeMock->method('isValidPasswordResetToken')->willReturn(true);

        return $userPasswordResetFacadeMock;
    }
}
