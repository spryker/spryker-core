<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\UserPasswordResetMail\Communication\UserPasswordReset;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Spryker\Zed\UserPasswordResetMail\Communication\Plugin\UserPasswordReset\MailUserPasswordResetRequestHandlerPlugin;
use Spryker\Zed\UserPasswordResetMail\Dependency\Facade\UserPasswordResetMailToMailFacadeBridge;
use Spryker\Zed\UserPasswordResetMail\UserPasswordResetMailDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group UserPasswordResetMail
 * @group Communication
 * @group UserPasswordReset
 * @group MailUserPasswordResetRequestHandlerPluginTest
 * Add your own group annotations below this line
 */
class MailUserPasswordResetRequestHandlerPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\UserPasswordResetMail\UserPasswordResetMailCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMailUserPasswordResetRequestHandlerPluginCallsMailFacade(): void
    {
        // Arrange
        $mailFacade = $this->createMailFacadeMock();
        $userTransfer = $this->tester->haveUser();
        $userPasswordResetRequestTransfer = (new UserPasswordResetRequestTransfer())
            ->setUser($userTransfer)
            ->setResetPasswordLink('');
        $mailUserPasswordResetPlugin = new MailUserPasswordResetRequestHandlerPlugin();

        // Assert
        $mailFacade->expects($this->once())
            ->method('handleMail');

        // Act
        $mailUserPasswordResetPlugin->handleUserPasswordResetRequest($userPasswordResetRequestTransfer);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    protected function createMailFacadeMock(): MailFacadeInterface
    {
        /** @var \Spryker\Zed\Mail\Business\MailFacadeInterface $mailFacade */
        $mailFacade = $this->getMockBuilder(MailFacadeInterface::class)->getMock();

        $this->tester->setDependency(
            UserPasswordResetMailDependencyProvider::FACADE_MAIL,
            new UserPasswordResetMailToMailFacadeBridge($mailFacade)
        );

        return $mailFacade;
    }
}
