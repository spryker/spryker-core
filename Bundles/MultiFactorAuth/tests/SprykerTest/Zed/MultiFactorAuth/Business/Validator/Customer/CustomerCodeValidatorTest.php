<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Business\Validator\Customer;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerCodeValidator;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;
use SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Business
 * @group Validator
 * @group Customer
 * @group CustomerCodeValidatorTest
 * Add your own group annotations below this line
 */
class CustomerCodeValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const INVALID_CODE_MESSAGE = 'Invalid code';

    /**
     * @var \SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester
     */
    protected MultiFactorAuthBusinessTester $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface
     */
    protected MockObject|MultiFactorAuthRepositoryInterface $repositoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface
     */
    protected MockObject|MultiFactorAuthEntityManagerInterface $entityManagerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface
     */
    protected MockObject|MultiFactorAuthToGlossaryFacadeInterface $glossaryFacadeMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerCodeValidator
     */
    protected CustomerCodeValidator $customerCodeValidator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(MultiFactorAuthRepositoryInterface::class);
        $this->entityManagerMock = $this->createMock(MultiFactorAuthEntityManagerInterface::class);
        $this->glossaryFacadeMock = $this->createMock(MultiFactorAuthToGlossaryFacadeInterface::class);
        $this->configMock = $this->createMock(MultiFactorAuthConfig::class);

        $this->customerCodeValidator = new CustomerCodeValidator(
            $this->repositoryMock,
            $this->entityManagerMock,
            $this->glossaryFacadeMock,
            $this->configMock,
        );
    }

    /**
     * @return void
     */
    public function testValidateReturnsSuccessfulResponseWhenCodeIsValid(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $validMultiFactorAuthCodeTransfer = $this->tester->createMultiFactorAuthCodeTransfer(
            $this->tester::EXPIRATION_DATE_VALID,
            0,
            $this->tester::VALID_CODE,
        );

        $this->repositoryMock->method('getCustomerCode')->willReturn($validMultiFactorAuthCodeTransfer);

        $response = $this->customerCodeValidator->validate($multiFactorAuthTransfer);

        $this->assertTrue((bool)$response->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateReturnsUnsuccessfulResponseWhenCodeIsInvalid(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $invalidMultiFactorAuthCodeTransfer = $this->tester->createMultiFactorAuthCodeTransfer(
            $this->tester::EXPIRATION_DATE_VALID,
            0,
            $this->tester::INVALID_CODE,
        );

        $this->repositoryMock->method('getCustomerCode')->willReturn($invalidMultiFactorAuthCodeTransfer);
        $this->glossaryFacadeMock->method('translate')->willReturn(static::INVALID_CODE_MESSAGE);

        $response = $this->customerCodeValidator->validate($multiFactorAuthTransfer);

        $this->assertEquals(MultiFactorAuthConstants::CODE_BLOCKED, $response->getStatus());
        $this->assertEquals(static::INVALID_CODE_MESSAGE, $response->getMessage());
    }
}
