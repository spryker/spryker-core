<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Business\Validator\User;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MultiFactorAuth\Business\Validator\User\UserMultiFactorAuthStatusValidator;
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
 * @group UserMultiFactorAuthStatusValidatorTest
 * Add your own group annotations below this line
 */
class UserMultiFactorAuthStatusValidatorTest extends Unit
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
     * @var \Spryker\Zed\MultiFactorAuth\Business\Validator\User\UserMultiFactorAuthStatusValidator
     */
    protected UserMultiFactorAuthStatusValidator $userMultiFactorAuthStatusValidator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(MultiFactorAuthRepositoryInterface::class);
        $this->userMultiFactorAuthStatusValidator = new UserMultiFactorAuthStatusValidator($this->repositoryMock);
    }

    /**
     * @return void
     */
    public function testValidateReturnsUnsuccessfulResponseWhenNoAuthTypes(): void
    {
        $this->repositoryMock->method('getUserMultiFactorAuthTypes')->willReturn(new MultiFactorAuthTypesCollectionTransfer());

        $response = $this->userMultiFactorAuthStatusValidator->validate($this->createMultiFactorAuthValidationRequestTransfer());

        $this->assertFalse($response->getIsRequired());
    }

    /**
     * @return void
     */
    public function testValidateReturnsSuccessfulResponseWhenAuthTypesExist(): void
    {
        $multiFactorAuthTypesCollectionTransfer = new MultiFactorAuthTypesCollectionTransfer();
        $multiFactorAuthTypesCollectionTransfer->addMultiFactorAuth($this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL));

        $this->repositoryMock->method('getUserMultiFactorAuthTypes')->willReturn($multiFactorAuthTypesCollectionTransfer);

        $response = $this->userMultiFactorAuthStatusValidator->validate($this->createMultiFactorAuthValidationRequestTransfer());

        $this->assertTrue($response->getIsRequired());
    }

    /**
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer
     */
    protected function createMultiFactorAuthValidationRequestTransfer(): MultiFactorAuthValidationRequestTransfer
    {
        return (new MultiFactorAuthValidationRequestTransfer())
            ->setUser((new UserTransfer())->setIdUser(1));
    }
}
