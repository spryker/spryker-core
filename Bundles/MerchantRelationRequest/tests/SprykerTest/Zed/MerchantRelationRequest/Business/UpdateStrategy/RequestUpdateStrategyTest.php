<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationRequest\Business\UpdateStrategy;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationshipCreatorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationRequestNotFoundException;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestApprovalUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestCancelationUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestPendingUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\RequestRejectionUpdateStrategy;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;
use SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationRequest
 * @group Business
 * @group UpdateStrategy
 * @group RequestUpdateStrategyTest
 * Add your own group annotations below this line
 */
class RequestUpdateStrategyTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_UUID = 'FAKE_UUID';

    /**
     * @var \SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester
     */
    protected MerchantRelationRequestBusinessTester $tester;

    /**
     * @return void
     */
    public function testThrowMerchantRelationRequestNotFoundExceptionInRequestApprovalUpdateStrategy(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = (new MerchantRelationRequestTransfer())->setUuid(static::FAKE_UUID);

        // Assert
        $this->expectException(MerchantRelationRequestNotFoundException::class);

        // Act
        $this->createRequestUpdateStrategyMock(RequestApprovalUpdateStrategy::class)->execute($merchantRelationRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowMerchantRelationRequestNotFoundExceptionInRequestCancelationUpdateStrategy(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = (new MerchantRelationRequestTransfer())->setUuid(static::FAKE_UUID);

        // Assert
        $this->expectException(MerchantRelationRequestNotFoundException::class);

        // Act
        $this->createRequestUpdateStrategyMock(RequestCancelationUpdateStrategy::class)->execute($merchantRelationRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowMerchantRelationRequestNotFoundExceptionInRequestRejectionUpdateStrategy(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = (new MerchantRelationRequestTransfer())->setUuid(static::FAKE_UUID);

        // Assert
        $this->expectException(MerchantRelationRequestNotFoundException::class);

        // Act
        $this->createRequestUpdateStrategyMock(RequestRejectionUpdateStrategy::class)->execute($merchantRelationRequestTransfer);
    }

    /**
     * @return void
     */
    public function testThrowMerchantRelationRequestNotFoundExceptionInRequestPendingUpdateStrategy(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = (new MerchantRelationRequestTransfer())->setUuid(static::FAKE_UUID);

        // Assert
        $this->expectException(MerchantRelationRequestNotFoundException::class);

        // Act
        $this->createRequestUpdateStrategyMock(RequestPendingUpdateStrategy::class)->execute($merchantRelationRequestTransfer);
    }

    /**
     * @param string $strategyClass
     *
     * @return \Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy\MerchantRelationRequestUpdaterStrategyInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createRequestUpdateStrategyMock(string $strategyClass): MerchantRelationRequestUpdaterStrategyInterface
    {
        return $this
            ->getMockBuilder($strategyClass)
            ->setConstructorArgs($this->prepareConstructorArgs())
            ->onlyMethods(['isApplicable'])
            ->getMock();
    }

    /**
     * @return list<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface>
     */
    protected function prepareConstructorArgs(): array
    {
        $merchantRelationRequestEntityManagerMock = $this
            ->getMockBuilder(MerchantRelationRequestEntityManagerInterface::class)
            ->getMock();

        $merchantRelationRequestReaderMock = $this
            ->getMockBuilder(MerchantRelationRequestReaderInterface::class)
            ->getMock();

        $merchantRelationRequestReaderMock
            ->method('findMerchantRelationRequestByUuid')
            ->willReturn(null);

        $merchantRelationRequestConfigMock = $this
            ->getMockBuilder(MerchantRelationRequestConfig::class)
            ->getMock();

        $merchantRelationshipCreatorMock = $this
            ->getMockBuilder(MerchantRelationshipCreatorInterface::class)
            ->getMock();

        return [
            $merchantRelationRequestEntityManagerMock,
            $merchantRelationRequestReaderMock,
            $merchantRelationRequestConfigMock,
            $merchantRelationshipCreatorMock,
        ];
    }
}
