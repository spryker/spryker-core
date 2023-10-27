<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesShipmentType\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeBusinessFactory;
use Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeFacade;
use Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeFacadeInterface;
use Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManager;
use Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface;
use SprykerTest\Zed\SalesShipmentType\SalesShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesShipmentType
 * @group Business
 * @group Facade
 * @group SaveSalesShipmentsWithSalesShipmentTypeTest
 * Add your own group annotations below this line
 */
class SaveSalesShipmentsWithSalesShipmentTypeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STATE_MACHINE_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesShipmentType\SalesShipmentTypeBusinessTester
     */
    protected SalesShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatesSalesShipmentTypeWhenItDoesNotExist(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $quoteTransfer = $this->tester->createQuoteTransfer([$shipmentTypeTransfer]);

        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);
        $shipmentTransfer = $this->tester->haveShipment($saveOrderTransfer->getIdSalesOrderOrFail());
        $saveOrderTransfer->getOrderItems()->getIterator()->current()->setShipment($shipmentTransfer);

        // Act
        $this->tester->getFacade()->saveSalesShipmentsWithSalesShipmentType($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentTypeEntity = $this->tester->findSalesShipmentTypeEntity(
            $shipmentTypeTransfer->getKeyOrFail(),
            $shipmentTypeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($salesShipmentTypeEntity);
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $salesShipmentTypeEntity->getName());
    }

    /**
     * @return void
     */
    public function testCreatesNewSalesShipmentTypeWhenItIsAlreadyExistAndShipmentTypeNameWasUpdated(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::NAME => 'some-new-name',
        ]);
        $salesShipmentTypeTransfer = $this->tester->haveSalesShipmentType([
            SalesShipmentTypeTransfer::KEY => $shipmentTypeTransfer->getKeyOrFail(),
            SalesShipmentTypeTransfer::NAME => 'some-old-name',
        ]);
        $quoteTransfer = $this->tester->createQuoteTransfer([$shipmentTypeTransfer]);

        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);
        $shipmentTransfer = $this->tester->haveShipment($saveOrderTransfer->getIdSalesOrderOrFail());
        $saveOrderTransfer->getOrderItems()->getIterator()->current()->setShipment($shipmentTransfer);

        // Act
        $this->tester->getFacade()->saveSalesShipmentsWithSalesShipmentType($quoteTransfer, $saveOrderTransfer);

        // Assert
        $newSalesShipmentTypeEntity = $this->tester->findSalesShipmentTypeEntity(
            $shipmentTypeTransfer->getKeyOrFail(),
            $shipmentTypeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($newSalesShipmentTypeEntity);
        $this->assertNotSame($salesShipmentTypeTransfer->getIdSalesShipmentTypeOrFail(), $newSalesShipmentTypeEntity->getIdSalesShipmentType());
        $this->assertSame($shipmentTypeTransfer->getNameOrFail(), $newSalesShipmentTypeEntity->getName());
        $this->assertSame($shipmentTypeTransfer->getKeyOrFail(), $newSalesShipmentTypeEntity->getKey());

        $oldSalesShipmentTypeEntity = $this->tester->findSalesShipmentTypeEntity(
            $salesShipmentTypeTransfer->getKeyOrFail(),
            $salesShipmentTypeTransfer->getNameOrFail(),
        );
        $this->assertNotNull($oldSalesShipmentTypeEntity);
        $this->assertSame($salesShipmentTypeTransfer->getIdSalesShipmentTypeOrFail(), $oldSalesShipmentTypeEntity->getIdSalesShipmentType());
        $this->assertSame($salesShipmentTypeTransfer->getNameOrFail(), $oldSalesShipmentTypeEntity->getName());
        $this->assertSame($salesShipmentTypeTransfer->getKeyOrFail(), $oldSalesShipmentTypeEntity->getKey());
    }

    /**
     * @return void
     */
    public function testDoNotUpdateSalesShipmentTypeWhenTheShipmentTypeWasNotChanged(): void
    {
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->haveSalesShipmentType([
            SalesShipmentTypeTransfer::KEY => $shipmentTypeTransfer->getKeyOrFail(),
            SalesShipmentTypeTransfer::NAME => $shipmentTypeTransfer->getNameOrFail(),
        ]);
        $quoteTransfer = $this->tester->createQuoteTransfer([$shipmentTypeTransfer]);

        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);
        $shipmentTransfer = $this->tester->haveShipment($saveOrderTransfer->getIdSalesOrderOrFail());
        $saveOrderTransfer->getOrderItems()->getIterator()->current()->setShipment($shipmentTransfer);

        // Assert
        $salesShipmentTypeEntityManager = $this->createSalesShipmentTypeEntityManagerMock();
        $salesShipmentTypeEntityManager->expects($this->never())
            ->method('createSalesShipmentType');
        $salesShipmentTypeBusinessFactory = $this->createSalesShipmentTypeBusinessFactoryMock($salesShipmentTypeEntityManager);
        $salesShipmentTypeFacade = $this->createSalesShipmentTypeFacadeMock($salesShipmentTypeBusinessFactory);

        // Act
        $salesShipmentTypeFacade->saveSalesShipmentsWithSalesShipmentType($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatesSalesShipmentWithIdSalesShipmentType(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $salesShipmentTypeTransfer = $this->tester->haveSalesShipmentType([
            SalesShipmentTypeTransfer::KEY => $shipmentTypeTransfer->getKeyOrFail(),
            SalesShipmentTypeTransfer::NAME => $shipmentTypeTransfer->getNameOrFail(),
        ]);
        $quoteTransfer = $this->tester->createQuoteTransfer([$shipmentTypeTransfer]);

        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);
        $shipmentTransfer = $this->tester->haveShipment($saveOrderTransfer->getIdSalesOrderOrFail());
        $saveOrderTransfer->getOrderItems()->getIterator()->current()->setShipment($shipmentTransfer);

        // Act
        $this->tester->getFacade()->saveSalesShipmentsWithSalesShipmentType($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentEntity = $this->tester->findSalesShipmentEntity($shipmentTransfer->getIdSalesShipmentOrFail());
        $this->assertNotNull($salesShipmentEntity);
        $this->assertSame($salesShipmentTypeTransfer->getIdSalesShipmentTypeOrFail(), $salesShipmentEntity->getFkSalesShipmentType());
    }

    /**
     * @return void
     */
    public function testUpdatesSalesShipmentWithIdSalesShipmentTypeForMultipleShipments(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();

        $salesShipmentTypeTransfer = $this->tester->haveSalesShipmentType([
            SalesShipmentTypeTransfer::KEY => $shipmentTypeTransfer1->getKeyOrFail(),
            SalesShipmentTypeTransfer::NAME => $shipmentTypeTransfer1->getNameOrFail(),
        ]);
        $quoteTransfer = $this->tester->createQuoteTransfer([
            $shipmentTypeTransfer1,
            $shipmentTypeTransfer2,
        ]);

        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);
        $shipmentTransfer1 = $this->tester->haveShipment($saveOrderTransfer->getIdSalesOrderOrFail());
        $shipmentTransfer2 = $this->tester->haveShipment($saveOrderTransfer->getIdSalesOrderOrFail());
        $itemTransferIterator = $saveOrderTransfer->getOrderItems()->getIterator();
        $itemTransferIterator->current()->setShipment($shipmentTransfer1);
        $itemTransferIterator->next();
        $itemTransferIterator->current()->setShipment($shipmentTransfer2);

        // Act
        $this->tester->getFacade()->saveSalesShipmentsWithSalesShipmentType($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentEntity1 = $this->tester->findSalesShipmentEntity($shipmentTransfer1->getIdSalesShipmentOrFail());
        $this->assertNotNull($salesShipmentEntity1);
        $this->assertSame($salesShipmentTypeTransfer->getIdSalesShipmentTypeOrFail(), $salesShipmentEntity1->getFkSalesShipmentType());

        $salesShipmentEntity2 = $this->tester->findSalesShipmentEntity($shipmentTransfer2->getIdSalesShipmentOrFail());
        $this->assertNotNull($salesShipmentEntity2);
        $this->assertNotNull($salesShipmentEntity2->getFkSalesShipmentType());
        $this->assertNotEquals($salesShipmentTypeTransfer->getIdSalesShipmentTypeOrFail(), $salesShipmentEntity2->getFkSalesShipmentType());
    }

    /**
     * @dataProvider doesNothingWhenRequiredDataIsNotProvidedDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testDoesNothingWhenExpectedDataIsNotProvided(ItemTransfer $itemTransfer): void
    {
        // Arrange
        $saveOrderTransfer = (new SaveOrderTransfer())->addOrderItem($itemTransfer);

        // Assert
        $salesShipmentTypeEntityManager = $this->createSalesShipmentTypeEntityManagerMock();
        $salesShipmentTypeEntityManager->expects($this->never())
            ->method('createSalesShipmentType');
        $salesShipmentTypeEntityManager->expects($this->never())
            ->method('updateSalesShipmentWithSalesShipmentType');
        $salesShipmentTypeBusinessFactory = $this->createSalesShipmentTypeBusinessFactoryMock($salesShipmentTypeEntityManager);
        $salesShipmentTypeFacade = $this->createSalesShipmentTypeFacadeMock($salesShipmentTypeBusinessFactory);

        // Act
        $salesShipmentTypeFacade->saveSalesShipmentsWithSalesShipmentType(new QuoteTransfer(), $saveOrderTransfer);
    }

    /**
     * @dataProvider throwsExceptionWhenRequiredDataIsNotProvidedDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredDataIsNotProvided(ItemTransfer $itemTransfer): void
    {
        // Arrange
        $saveOrderTransfer = (new SaveOrderTransfer())->addOrderItem($itemTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->saveSalesShipmentsWithSalesShipmentType(new QuoteTransfer(), $saveOrderTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    protected function doesNothingWhenRequiredDataIsNotProvidedDataProvider(): array
    {
        return [
            'item without shipment' => [
                (new ItemBuilder([ItemTransfer::SHIPMENT => null]))->build(),
            ],
            'item without id sales shipment' => [
                (new ItemBuilder())
                    ->withShipment([ShipmentTransfer::ID_SALES_SHIPMENT => null])
                    ->build(),
            ],
            'item without shipment type' => [
                (new ItemBuilder())
                    ->withShipment([ShipmentTransfer::ID_SALES_SHIPMENT => rand()])
                    ->build(),
            ],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    protected function throwsExceptionWhenRequiredDataIsNotProvidedDataProvider(): array
    {
        return [
            'item without shipment type' => [
                (new ItemBuilder())
                    ->withShipment([ShipmentTransfer::ID_SALES_SHIPMENT => rand()])
                    ->withShipmentType([ShipmentTypeTransfer::KEY => null])
                    ->build(),
            ],
        ];
    }

    /**
     * @param \Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeBusinessFactory $salesShipmentTypeBusinessFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeFacadeInterface
     */
    protected function createSalesShipmentTypeFacadeMock(SalesShipmentTypeBusinessFactory $salesShipmentTypeBusinessFactory): SalesShipmentTypeFacadeInterface
    {
        $salesShipmentTypeFacadeMock = $this->getMockBuilder(SalesShipmentTypeFacade::class)
            ->enableProxyingToOriginalMethods()
            ->onlyMethods(['getFactory'])
            ->getMock();
        $salesShipmentTypeFacadeMock
            ->method('getFactory')
            ->willReturn($salesShipmentTypeBusinessFactory);

        return $salesShipmentTypeFacadeMock;
    }

    /**
     * @param \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface $salesShipmentTypeEntityManagerMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeBusinessFactory
     */
    protected function createSalesShipmentTypeBusinessFactoryMock(
        SalesShipmentTypeEntityManagerInterface $salesShipmentTypeEntityManagerMock
    ): SalesShipmentTypeBusinessFactory {
        $salesShipmentTypeBusinessFactory = $this->getMockBuilder(SalesShipmentTypeBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->onlyMethods([
                'getEntityManager',
            ])
            ->getMock();

        $salesShipmentTypeBusinessFactory
            ->method('getEntityManager')
            ->willReturn($salesShipmentTypeEntityManagerMock);

        return $salesShipmentTypeBusinessFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface
     */
    protected function createSalesShipmentTypeEntityManagerMock(): SalesShipmentTypeEntityManagerInterface
    {
        $salesShipmentTypeEntityManagerMock = $this->getMockBuilder(SalesShipmentTypeEntityManager::class)
            ->getMock();
        $salesShipmentTypeEntityManagerMock
            ->method('createSalesShipmentType')
            ->willReturnArgument(1);

        return $salesShipmentTypeEntityManagerMock;
    }
}
