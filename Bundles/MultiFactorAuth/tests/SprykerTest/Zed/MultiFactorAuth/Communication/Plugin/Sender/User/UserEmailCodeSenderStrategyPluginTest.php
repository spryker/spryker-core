<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Communication\Plugin\Sender\User;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory;
use Spryker\Zed\MultiFactorAuth\Communication\Plugin\Sender\User\UserEmailCodeSenderStrategyPlugin;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface;
use SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Communication
 * @group Plugin
 * @group Sender
 * @group User
 * @group UserEmailCodeSenderStrategyPluginTest
 * Add your own group annotations below this line
 */
class UserEmailCodeSenderStrategyPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TYPE_SMS = 'sms';

    /**
     * @var \SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthCommunicationTester
     */
    protected MultiFactorAuthCommunicationTester $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface
     */
    protected MockObject|MultiFactorAuthToMailFacadeInterface $mailFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory
     */
    protected MockObject|MultiFactorAuthCommunicationFactory $factoryMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Communication\Plugin\Sender\User\UserEmailCodeSenderStrategyPlugin
     */
    protected UserEmailCodeSenderStrategyPlugin $userEmailCodeSenderStrategyPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mailFacadeMock = $this->createMock(MultiFactorAuthToMailFacadeInterface::class);
        $this->factoryMock = $this->createMock(MultiFactorAuthCommunicationFactory::class);
        $this->factoryMock->method('getMailFacade')->willReturn($this->mailFacadeMock);

        $this->userEmailCodeSenderStrategyPlugin = new UserEmailCodeSenderStrategyPlugin();
        $this->userEmailCodeSenderStrategyPlugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueWhenTypeIsEmail(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $result = $this->userEmailCodeSenderStrategyPlugin->isApplicable($multiFactorAuthTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseWhenTypeIsNotEmail(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer(static::TYPE_SMS);

        $result = $this->userEmailCodeSenderStrategyPlugin->isApplicable($multiFactorAuthTransfer);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testSendCallsHandleMail(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $this->mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->with($this->isInstanceOf(MailTransfer::class));

        $this->userEmailCodeSenderStrategyPlugin->send($multiFactorAuthTransfer);
    }
}
