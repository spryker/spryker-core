<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Business\Validator\User;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\Validator\User\UserCodeValidator;
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
 * @group User
 * @group UserCodeValidatorTest
 * Add your own group annotations below this line
 */
class UserCodeValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const INVALID_CODE_MESSAGE = 'Invalid code';

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
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface
     */
    protected MockObject|MultiFactorAuthEntityManagerInterface $entityManagerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface
     */
    protected MockObject|MultiFactorAuthToGlossaryFacadeInterface $glossaryFacadeMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Validator\User\UserCodeValidator
     */
    protected UserCodeValidator $userCodeValidator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(MultiFactorAuthRepositoryInterface::class);
        $this->entityManagerMock = $this->createMock(MultiFactorAuthEntityManagerInterface::class);
        $this->glossaryFacadeMock = $this->createMock(MultiFactorAuthToGlossaryFacadeInterface::class);

        $this->glossaryFacadeMock->method('translate')->willReturn(static::INVALID_CODE_MESSAGE);

        $config = new MultiFactorAuthConfig();

        $this->userCodeValidator = new UserCodeValidator(
            $this->repositoryMock,
            $this->entityManagerMock,
            $this->glossaryFacadeMock,
            $config,
        );
    }

    /**
     * @return void
     */
    public function testValidateReturnsTrueWhenCodeIsValid(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $validMultiFactorAuthCodeTransfer = $this->tester->createMultiFactorAuthCodeTransfer(
            $this->tester::EXPIRATION_DATE_VALID,
            0,
            $this->tester::VALID_CODE,
        );

        $this->repositoryMock->method('getUserCode')->willReturn($validMultiFactorAuthCodeTransfer);

        $result = $this->userCodeValidator->validate($multiFactorAuthTransfer);

        $this->assertEquals(MultiFactorAuthConstants::CODE_VERIFIED, $result->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateReturnsFalseWhenCodeIsExpired(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);

        $expiredMultiFactorAuthCodeTransfer = $this->tester->createMultiFactorAuthCodeTransfer(
            static::EXPIRATION_DATE_INVALID,
            0,
            $this->tester::VALID_CODE,
        );

        $this->repositoryMock->method('getUserCode')->willReturn($expiredMultiFactorAuthCodeTransfer);

        $result = $this->userCodeValidator->validate($multiFactorAuthTransfer);

        $this->assertEquals(MultiFactorAuthConstants::CODE_BLOCKED, $result->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateReturnsFalseWhenCodeIsInvalid(): void
    {
        $multiFactorAuthTransfer = $this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL);
        $invalidMultiFactorAuthCodeTransfer = $this->tester->createMultiFactorAuthCodeTransfer(
            $this->tester::EXPIRATION_DATE_VALID,
            0,
            $this->tester::INVALID_CODE,
        );

        $this->repositoryMock->method('getUserCode')->willReturn($invalidMultiFactorAuthCodeTransfer);

        $result = $this->userCodeValidator->validate($multiFactorAuthTransfer);

        $this->assertEquals(MultiFactorAuthConstants::CODE_UNVERIFIED, $result->getStatus());
    }
}
