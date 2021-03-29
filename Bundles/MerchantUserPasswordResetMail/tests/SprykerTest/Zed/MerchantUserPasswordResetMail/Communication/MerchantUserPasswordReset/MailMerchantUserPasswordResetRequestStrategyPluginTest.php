<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUserPasswordResetMail\Communication\UserPasswordReset;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Spryker\Zed\MerchantUserPasswordResetMail\Communication\Plugin\UserPasswordReset\MailMerchantUserPasswordResetRequestStrategyPlugin;
use Spryker\Zed\MerchantUserPasswordResetMail\Dependency\Facade\MerchantUserPasswordResetMailToMailFacadeBridge;
use Spryker\Zed\MerchantUserPasswordResetMail\MerchantUserPasswordResetMailDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantUserPasswordResetMail
 * @group Communication
 * @group UserPasswordReset
 * @group MailMerchantUserPasswordResetRequestStrategyPluginTest
 * Add your own group annotations below this line
 */
class MailMerchantUserPasswordResetRequestStrategyPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantUserPasswordResetMail\MerchantUserPasswordResetMailCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMailMerchantUserPasswordResetRequestStrategyPluginCallsMailFacade(): void
    {
        // Arrange
        $mailFacade = $this->createMailFacadeMock();

        $userTransfer = $this->tester->haveUser();
        $userPasswordResetRequestTransfer = (new UserPasswordResetRequestTransfer())
            ->setUser($userTransfer)
            ->setResetPasswordLink('');
        $mailMerchantUserPasswordResetPlugin = new MailMerchantUserPasswordResetRequestStrategyPlugin();

        // Assert
        $mailFacade->expects($this->once())
            ->method('handleMail');

        // Act
        $mailMerchantUserPasswordResetPlugin->handleUserPasswordResetRequest($userPasswordResetRequestTransfer);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    protected function createMailFacadeMock(): MailFacadeInterface
    {
        /** @var \Spryker\Zed\Mail\Business\MailFacadeInterface $mailFacade */
        $mailFacade = $this->getMockBuilder(MailFacadeInterface::class)->getMock();

        $this->tester->setDependency(
            MerchantUserPasswordResetMailDependencyProvider::FACADE_MAIL,
            new MerchantUserPasswordResetMailToMailFacadeBridge($mailFacade)
        );

        return $mailFacade;
    }
}
