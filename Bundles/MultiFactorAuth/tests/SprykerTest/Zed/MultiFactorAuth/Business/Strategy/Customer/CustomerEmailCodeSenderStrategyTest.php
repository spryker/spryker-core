<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Business\Strategy\Customer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MultiFactorAuth\Business\Strategy\Customer\CustomerEmailCodeSenderStrategy;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface;
use SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Business
 * @group Strategy
 * @group Customer
 * @group CustomerEmailCodeSenderStrategyTest
 * Add your own group annotations below this line
 */
class CustomerEmailCodeSenderStrategyTest extends Unit
{
    /**
     * @var string
     */
    protected const TYPE_SMS = 'sms';

    /**
     * @var \SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester
     */
    protected MultiFactorAuthBusinessTester $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface
     */
    protected MockObject|MultiFactorAuthToMailFacadeInterface $mailFacadeMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Strategy\Customer\CustomerEmailCodeSenderStrategy
     */
    protected CustomerEmailCodeSenderStrategy $customerEmailCodeSenderStrategy;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mailFacadeMock = $this->createMock(MultiFactorAuthToMailFacadeInterface::class);

        $this->customerEmailCodeSenderStrategy = new CustomerEmailCodeSenderStrategy(
            $this->mailFacadeMock,
        );
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueWhenTypeIsEmail(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $result = $this->customerEmailCodeSenderStrategy->isApplicable($multiFactorAuthTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseWhenTypeIsNotEmail(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer(static::TYPE_SMS);

        $result = $this->customerEmailCodeSenderStrategy->isApplicable($multiFactorAuthTransfer);

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

        $this->customerEmailCodeSenderStrategy->send($multiFactorAuthTransfer);
    }
}
