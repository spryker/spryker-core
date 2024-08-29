<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OrderMatrix\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer;
use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;
use Generated\Shared\Transfer\OrderMatrixTransfer;
use Spryker\Client\StorageRedis\StorageRedisClientInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\OrderMatrix\Business\OrderMatrixFacadeInterface;
use Spryker\Zed\OrderMatrix\Dependency\Facade\OrderMatrixToOmsFacadeInterface;
use Spryker\Zed\OrderMatrix\OrderMatrixDependencyProvider;
use SprykerTest\Zed\OrderMatrix\OrderMatrixBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OrderMatrix
 * @group Business
 * @group Facade
 * @group OrderMatrixFacadeTest
 * Add your own group annotations below this line
 */
class OrderMatrixFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OrderMatrix\OrderMatrixBusinessTester
     */
    protected OrderMatrixBusinessTester $tester;

    /**
     * @var \Spryker\Zed\OrderMatrix\Business\OrderMatrixFacadeInterface
     */
    protected OrderMatrixFacadeInterface $orderMatrixFacade;

    /**
     * @var \Spryker\Client\StorageRedis\StorageRedisClientInterface
     */
    protected StorageRedisClientInterface $storageRedisClient;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected UtilEncodingServiceInterface $utilEncodingService;

    /**
     * @dataProvider orderMatrixDataProvider
     *
     * @param array<int, string> $processes
     * @param \Generated\Shared\Transfer\OrderMatrixCollectionTransfer $matrixOrderItemsBatch
     * @param string $expectedResult
     *
     * @return void
     */
    public function testWriteOrderMatrixShouldReturnExpectedResult(
        array $processes,
        OrderMatrixCollectionTransfer $matrixOrderItemsBatch,
        string $expectedResult
    ): void {
        // Arrange
        $omsFacadeMock = $this->getMockBuilder(OrderMatrixToOmsFacadeInterface::class)
            ->onlyMethods(['getProcessNamesIndexedByIdOmsOrderProcess', 'getOrderMatrixCollection'])
            ->getMock();
        $omsFacadeMock->method('getProcessNamesIndexedByIdOmsOrderProcess')->willReturn($processes);
        $omsFacadeMock->method('getOrderMatrixCollection')->willReturn($matrixOrderItemsBatch, new OrderMatrixCollectionTransfer());
        $this->tester->setDependency(OrderMatrixDependencyProvider::FACADE_OMS, $omsFacadeMock);
        // Act
        $orderMatrixFacade = $this->tester->getFacade();
        $orderMatrixFacade->writeOrderMatrix();
        $orderMatrices = $this->storageRedisClient->get('order_matrix');
        $encodedOrderMatrices = $this->utilEncodingService->encodeJson($orderMatrices, true);
        // Assert
        $this->tester->assertEquals($expectedResult, $encodedOrderMatrices);
    }

    /**
     * @dataProvider findOrderMatrixDataProvider
     *
     * @param string $matrices
     *
     * @return void
     */
    public function testFindOrderMatrixShouldReturnExpectedResult(string $matrices): void
    {
        // Arrange
        $this->storageRedisClient->set('order_matrix', $matrices);

        // Act
        $result = $this->orderMatrixFacade->getOrderMatrixStatistics();
        $expectedResult = $this->utilEncodingService->decodeJson($matrices, true);

        // Assert
        $this->assertInstanceOf(IndexedOrderMatrixResponseTransfer::class, $result);
        $this->tester->assertEquals($expectedResult, $result->getMatrices());
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->storageRedisClient = $this->tester->getLocator()->storageRedis()->client();
        $this->orderMatrixFacade = $this->tester->getFacade();
        $this->utilEncodingService = $this->tester->getLocator()->utilEncoding()->service();
        $this->storageRedisClient->delete('order_matrix');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function orderMatrixDataProvider(): array
    {
        $orderMatrixCollectionTransfer = (new OrderMatrixCollectionTransfer())
            ->addOrderMatrix(
                (new OrderMatrixTransfer())->setIdProcess(1)
                    ->setProcessName('test_process_1')
                    ->setIdState(1)
                    ->setStateName('test_state_1')
                    ->setDateWindow('day')
                    ->setItemsCount(1),
            )->addOrderMatrix(
                (new OrderMatrixTransfer())->setIdProcess(1)
                    ->setProcessName('test_process_1')
                    ->setIdState(1)
                    ->setStateName('test_state_1')
                    ->setDateWindow('week')
                    ->setItemsCount(1),
            );

        return [
            'Should save expected data to the database' => [
                'processes' => [
                    1 => 'test_process_1',
                    2 => 'test_process_2',
                ],
                'matrixOrderItemsBatch' => $orderMatrixCollectionTransfer,
                'expectedResult' => '{"1:test_state_1":{"1:test_process_1":{"day":1,"week":1}}}',
            ],
            'There are no orders, should save empty array to the database' => [
                'processes' => [
                    1 => 'test_process_1',
                    2 => 'test_process_2',
                ],
                'matrixOrderItemsBatch' => new OrderMatrixCollectionTransfer(),
                'expectedResult' => '[]',
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    protected function findOrderMatrixDataProvider(): array
    {
        return [
            'Matrix has values' => [
                'matrices' => '{"test_key": "test_value"}',
            ],
            'Matrix is empty' => [
                'matrices' => '[]',
            ],
        ];
    }
}
