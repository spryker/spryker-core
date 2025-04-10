<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Business\Sender\Customer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Sender\Customer\CustomerCodeSender;
use Spryker\Zed\MultiFactorAuth\Business\Strategy\SendStrategyInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface;
use SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Business
 * @group Sender
 * @group Customer
 * @group CustomerCodeSenderTest
 * Add your own group annotations below this line
 */
class CustomerCodeSenderTest extends Unit
{
    /**
     * @var int
     */
    protected const CODE_VALIDITY_TTL = 10;

    /**
     * @var \SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester
     */
    protected MultiFactorAuthBusinessTester $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface
     */
    protected MockObject|MultiFactorAuthEntityManagerInterface $entityManagerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface
     */
    protected MockObject|CodeGeneratorInterface $codeGeneratorMock;

    /**
     * @var array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Business\Strategy\SendStrategyInterface>
     */
    protected array $sendStrategiesMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Sender\Customer\CustomerCodeSender
     */
    protected CustomerCodeSender $customerCodeSender;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManagerMock = $this->createMock(MultiFactorAuthEntityManagerInterface::class);
        $this->codeGeneratorMock = $this->createMock(CodeGeneratorInterface::class);
        $this->sendStrategiesMock = [$this->createMock(SendStrategyInterface::class)];

        $this->customerCodeSender = new CustomerCodeSender(
            $this->entityManagerMock,
            $this->codeGeneratorMock,
            $this->sendStrategiesMock,
        );
    }

    /**
     * @return void
     */
    public function testSendCodeReturnsMultiFactorAuthTransferWhenTypeIsEmail(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $this->codeGeneratorMock->expects($this->once())
            ->method('generateCode')
            ->with($this->isInstanceOf(MultiFactorAuthTransfer::class));

        $this->entityManagerMock->expects($this->once())
            ->method('saveCustomerCode')
            ->with($this->isInstanceOf(MultiFactorAuthTransfer::class));

        $result = $this->customerCodeSender->sendCode($multiFactorAuthTransfer);

        $this->assertInstanceOf(MultiFactorAuthTransfer::class, $result);
        $this->assertEquals($this->tester::VALID_CODE, $result->getMultiFactorAuthCode()->getCode());
    }

    /**
     * @return void
     */
    public function testSendCodeReturnsMultiFactorAuthTransferWhenNoStrategyIsApplicable(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $this->codeGeneratorMock->expects($this->once())
            ->method('generateCode')
            ->with($this->isInstanceOf(MultiFactorAuthTransfer::class));

        $this->entityManagerMock->expects($this->once())
            ->method('saveCustomerCode')
            ->with($this->isInstanceOf(MultiFactorAuthTransfer::class));

        $this->sendStrategiesMock[0]->expects($this->once())
            ->method('isApplicable')
            ->willReturn(false);

        $result = $this->customerCodeSender->sendCode($multiFactorAuthTransfer);

        $this->assertInstanceOf(MultiFactorAuthTransfer::class, $result);
        $this->assertEquals($this->tester::VALID_CODE, $result->getMultiFactorAuthCode()->getCode());
    }
}
