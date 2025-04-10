<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Business\Validator\Customer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerMultiFactorAuthStatusValidator;
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
 * @group CustomerMultiFactorAuthStatusValidatorTest
 * Add your own group annotations below this line
 */
class CustomerMultiFactorAuthStatusValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const EXPIRATION_DATE_INVALID = '-1 hour';

    /**
     * @var \SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester
     */
    protected MultiFactorAuthBusinessTester $tester;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface
     */
    protected MockObject|MultiFactorAuthRepositoryInterface $repositoryMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerMultiFactorAuthStatusValidator
     */
    protected CustomerMultiFactorAuthStatusValidator $customerMultiFactorAuthStatusValidator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(MultiFactorAuthRepositoryInterface::class);
        $this->customerMultiFactorAuthStatusValidator = new CustomerMultiFactorAuthStatusValidator($this->repositoryMock);
    }

    /**
     * @return void
     */
    public function testValidateReturnsUnsuccessfulResponseWhenNoAuthTypes(): void
    {
        $this->repositoryMock->method('getCustomerMultiFactorAuthTypes')->willReturn(new MultiFactorAuthTypesCollectionTransfer());

        $response = $this->customerMultiFactorAuthStatusValidator->validate($this->createMultiFactorAuthValidationRequestTransfer());

        $this->assertFalse($response->getIsRequired());
    }

    /**
     * @return void
     */
    public function testValidateReturnsSuccessfulResponseWhenCodeIsValid(): void
    {
        $validMultiFactorAuthCodeTransfer = $this->tester->createMultiFactorAuthCodeTransfer(
            $this->tester::EXPIRATION_DATE_VALID,
            1,
            $this->tester::VALID_CODE,
        );

        $multiFactorAuthTypesCollectionTransfer = $this->tester->createMultiFactorAuthTypesCollectionTransfer($this->tester::TYPE_EMAIL);

        $this->repositoryMock->method('getCustomerMultiFactorAuthTypes')->willReturn($multiFactorAuthTypesCollectionTransfer);
        $this->repositoryMock->method('getCustomerCode')->willReturn($validMultiFactorAuthCodeTransfer);

        $response = $this->customerMultiFactorAuthStatusValidator->validate($this->createMultiFactorAuthValidationRequestTransfer());

        $this->assertTrue($response->getIsRequired());
        $this->assertTrue((bool)$response->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateReturnsUnsuccessfulResponseWhenCodeIsInvalid(): void
    {
        $invalidMultiFactorAuthCodeTransfer = $this->tester->createMultiFactorAuthCodeTransfer(
            static::EXPIRATION_DATE_INVALID,
            0,
        );

        $multiFactorAuthTypesCollectionTransfer = $this->tester->createMultiFactorAuthTypesCollectionTransfer($this->tester::TYPE_EMAIL);

        $this->repositoryMock->method('getCustomerMultiFactorAuthTypes')->willReturn($multiFactorAuthTypesCollectionTransfer);
        $this->repositoryMock->method('getCustomerCode')->willReturn($invalidMultiFactorAuthCodeTransfer);

        $response = $this->customerMultiFactorAuthStatusValidator->validate($this->createMultiFactorAuthValidationRequestTransfer());

        $this->assertTrue($response->getIsRequired());
        $this->assertFalse((bool)$response->getStatus());
    }

    /**
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer
     */
    protected function createMultiFactorAuthValidationRequestTransfer(): MultiFactorAuthValidationRequestTransfer
    {
        return (new MultiFactorAuthValidationRequestTransfer())->setCustomer(new CustomerTransfer());
    }
}
