<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\MultiFactorAuth\Communication\Activator\User;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Activator\User\UserMultiFactorAuthActivator;
use Spryker\Zed\MultiFactorAuth\Communication\Controller\UserController;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Communication
 * @group Activator
 * @group User
 * @group UserMultiFactorAuthActivatorTest
 * Add your own group annotations below this line
 */
class UserMultiFactorAuthActivatorTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_TYPE = 'test-type';

    /**
     * @var int
     */
    protected const TEST_USER_ID = 1;

    /**
     * @var string
     */
    protected const TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected UserTransfer $userTransfer;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface
     */
    protected $facadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface
     */
    protected $requestReaderMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Communication\Activator\User\UserMultiFactorAuthActivator
     */
    protected UserMultiFactorAuthActivator $userMultiFactorAuthActivator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userTransfer = (new UserTransfer())->setIdUser(static::TEST_USER_ID);

        $this->facadeMock = $this->createMultiFactorAuthFacadeMock();

        $this->requestReaderMock = $this->getMockBuilder(RequestReaderInterface::class)
            ->getMock();

        $this->userMultiFactorAuthActivator = new UserMultiFactorAuthActivator($this->facadeMock, $this->requestReaderMock);
    }

    /**
     * @return void
     */
    public function testActivateCallsFacadeWithProperData(): void
    {
        // Arrange
        $request = new Request();

        $this->facadeMock
            ->expects($this->once())
            ->method('activateUserMultiFactorAuth')
            ->with(
                $this->callback(function (MultiFactorAuthTransfer $authTransfer) {
                    return $authTransfer->getUser()->getIdUser() === $this->userTransfer->getIdUser()
                        && $authTransfer->getType() === static::TEST_TYPE
                        && $authTransfer->getStatus() === MultiFactorAuthConstants::STATUS_ACTIVE;
                }),
            );

        $this->requestReaderMock
            ->method('get')
            ->willReturnMap([
                [$request, UserController::IS_ACTIVATION, null, false],
                [$request, 'type_to_set_up', null, null],
            ]);

        $request->query->set('type', static::TEST_TYPE);

        // Act
        $this->userMultiFactorAuthActivator->activate($request, $this->userTransfer);
    }

    /**
     * @return void
     */
    public function testActivateCallsFacadeWithProperDataWhenPendingActivation(): void
    {
        // Arrange
        $request = new Request();

        $this->facadeMock
            ->expects($this->once())
            ->method('activateUserMultiFactorAuth')
            ->with(
                $this->callback(function (MultiFactorAuthTransfer $authTransfer) {
                    return $authTransfer->getUser()->getIdUser() === $this->userTransfer->getIdUser()
                        && $authTransfer->getType() === static::TEST_TYPE
                        && $authTransfer->getStatus() === MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION;
                }),
            );

        $this->requestReaderMock
            ->method('get')
            ->willReturnMap([
                [$request, UserController::IS_ACTIVATION, null, true],
                [$request, 'type_to_set_up', null, static::TEST_TYPE],
            ]);

        // Act
        $this->userMultiFactorAuthActivator->activate($request, $this->userTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface
     */
    protected function createMultiFactorAuthFacadeMock(): MultiFactorAuthFacadeInterface
    {
        return $this->createMock(MultiFactorAuthFacadeInterface::class);
    }
}
