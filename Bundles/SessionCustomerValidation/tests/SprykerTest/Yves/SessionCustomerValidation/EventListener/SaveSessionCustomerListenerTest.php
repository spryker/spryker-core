<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\SessionCustomerValidation\EventListener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface;
use Spryker\Yves\SessionCustomerValidation\EventListener\SaveSessionCustomerListener;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface;
use SprykerTest\Yves\SessionCustomerValidation\SessionCustomerValidationYvesTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionCustomerValidation
 * @group EventListener
 * @group SaveSessionCustomerListenerTest
 * Add your own group annotations below this line
 */
class SaveSessionCustomerListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Yves\SessionCustomerValidation\SessionCustomerValidationYvesTester
     */
    protected SessionCustomerValidationYvesTester $tester;

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionCustomerDataWhenRequestDoesNotHaveSession(): void
    {
        // Arrange
        $saverPluginMock = $this->createSessionCustomerSaverPluginMock();
        $saveSessionCustomerListener = new SaveSessionCustomerListener(
            $saverPluginMock,
            $this->createCustomerClientMock(new CustomerTransfer()),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(),
            $this->createAuthenticationTokenMock(),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSessionCustomer');

        // Act
        $saveSessionCustomerListener->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionCustomerDataWhenUserIsNotSet(): void
    {
        // Arrange
        $saverPluginMock = $this->createSessionCustomerSaverPluginMock();
        $saveSessionCustomerListener = new SaveSessionCustomerListener(
            $saverPluginMock,
            $this->createCustomerClientMock(new CustomerTransfer()),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSessionCustomer');

        // Act
        $saveSessionCustomerListener->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldNotSaveSessionCustomerDataWhenCustomerDoesNotHaveId(): void
    {
        // Arrange
        $saverPluginMock = $this->createSessionCustomerSaverPluginMock();
        $saveSessionCustomerListener = new SaveSessionCustomerListener(
            $saverPluginMock,
            $this->createCustomerClientMock(new CustomerTransfer()),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(true),
        );

        // Assert
        $saverPluginMock->expects($this->never())->method('saveSessionCustomer');

        // Act
        $saveSessionCustomerListener->onInteractiveLogin($event);
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginShouldSaveSessionCustomerDataWhenCustomerHasId(): void
    {
        // Arrange
        $saverPluginMock = $this->createSessionCustomerSaverPluginMock();
        $saveSessionCustomerListener = new SaveSessionCustomerListener(
            $saverPluginMock,
            $this->createCustomerClientMock((new CustomerTransfer())->setIdCustomer(1)),
        );

        $event = new InteractiveLoginEvent(
            $this->createRequestMock(true),
            $this->createAuthenticationTokenMock(true),
        );

        // Assert
        $saverPluginMock->expects($this->once())->method('saveSessionCustomer');

        // Act
        $saveSessionCustomerListener->onInteractiveLogin($event);
    }

    /**
     * @param bool $withSession
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function createRequestMock(bool $withSession = false): Request
    {
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();

        $requestMock->method('hasSession')
            ->willReturn($withSession);

        if (!$withSession) {
            return $requestMock;
        }

        $sessionMock = $this->getMockBuilder(SessionInterface::class)
            ->getMock();

        $sessionMock->method('getId')
            ->willReturn('1');

        $requestMock->method('getSession')
            ->willReturn($sessionMock);

        return $requestMock;
    }

    /**
     * @param bool $withUser
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    protected function createAuthenticationTokenMock(bool $withUser = false): TokenInterface
    {
        $authenticationTokenMock = $this->getMockBuilder(TokenInterface::class)
            ->getMock();

        if (!$withUser) {
            return $authenticationTokenMock;
        }

        $authenticationTokenMock->method('getUser')
            ->willReturn($this->createUserMock());

        return $authenticationTokenMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\User\UserInterface
     */
    protected function createUserMock(): UserInterface
    {
        return $this->getMockBuilder(UserInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface
     */
    protected function createSessionCustomerSaverPluginMock(): SessionCustomerSaverPluginInterface
    {
        return $this->getMockBuilder(SessionCustomerSaverPluginInterface::class)
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientInterface
     */
    protected function createCustomerClientMock(CustomerTransfer $customerTransfer): SessionCustomerValidationToCustomerClientInterface
    {
        $customerClientMock = $this->getMockBuilder(SessionCustomerValidationToCustomerClientInterface::class)
            ->getMock();

        $customerClientMock->method('getCustomerByEmail')
            ->willReturn($customerTransfer);

        return $customerClientMock;
    }
}
