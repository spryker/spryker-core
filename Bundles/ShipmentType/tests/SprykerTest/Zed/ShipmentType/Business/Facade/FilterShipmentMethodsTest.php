<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentType\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentGroupBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodsBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ShipmentType\ShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentType
 * @group Business
 * @group Facade
 * @group FilterShipmentMethodsTest
 * Add your own group annotations below this line
 */
class FilterShipmentMethodsTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\ShipmentType\ShipmentTypeBusinessTester
     */
    protected ShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    public function testDoesNotFilterOutShipmentMethodWithoutShipmentTypeRelation(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())->addMethod($shipmentMethodTransfer);

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem([ItemTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer])
            ->withAvailableShipmentMethods($shipmentMethodsTransfer->toArray())
            ->build();

        // Act
        $shipmentMethodTransfers = $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfers->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNotFilterOutShipmentMethodsIfShipmentTypeIsNotProvidedInItemTransfer(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())->addMethod($shipmentMethodTransfer);

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem([ItemTransfer::SHIPMENT_TYPE => null])
            ->withAvailableShipmentMethods($shipmentMethodsTransfer->toArray())
            ->build();

        // Act
        $shipmentMethodTransfers = $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfers->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNotFilterOutShipmentMethodsWhenShipmentTypeIsProvidedInItemShipmentMethod(): void
    {
        // Arrange
        $shipmentMethodTransfer1 = $this->tester->haveShipmentMethod();
        $shipmentMethodTransfer2 = $this->tester->haveShipmentMethod();
        $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())
            ->addMethod($shipmentMethodTransfer1)
            ->addMethod($shipmentMethodTransfer2);

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
        );
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer2->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer2->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem(
                (new ItemBuilder())
                    ->withShipment([
                        ShipmentTransfer::METHOD => [
                            ShipmentMethodTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer1->toArray(),
                        ],
                    ]),
            )
            ->withAvailableShipmentMethods($shipmentMethodsTransfer->toArray())
            ->build();

        // Act
        $shipmentMethodTransfers = $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $this->assertSame(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfers->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNotFilterOutShipmentMethodRelatedToActiveShipmentTypeWithStoreRelation(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())->addMethod($shipmentMethodTransfer);

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem([ItemTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer])
            ->withAvailableShipmentMethods($shipmentMethodsTransfer->toArray())
            ->build();

        // Act
        $shipmentMethodTransfers = $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $this->assertSame(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfers->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testFiltersOutShipmentMethodRelatedToInactiveShipmentType(): void
    {
        // Arrange
        $shipmentMethodTransfer1 = $this->tester->haveShipmentMethod();
        $shipmentMethodTransfer2 = $this->tester->haveShipmentMethod();
        $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())
            ->addMethod($shipmentMethodTransfer1)
            ->addMethod($shipmentMethodTransfer2);

        $storeTransfer = $this->tester->haveStore();
        $activeShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $inactiveShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $activeShipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer2->getIdShipmentMethodOrFail(),
            $inactiveShipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem([ItemTransfer::SHIPMENT_TYPE => $activeShipmentTypeTransfer])
            ->withAnotherItem([ItemTransfer::SHIPMENT_TYPE => $inactiveShipmentTypeTransfer])
            ->withAvailableShipmentMethods($shipmentMethodsTransfer->toArray())
            ->build();

        // Act
        $shipmentMethodTransfers = $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $this->assertSame(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfers->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testFiltersOutShipmentMethodRelatedToShipmentTypeWhichIsNotAvailableForStore(): void
    {
        // Arrange
        $shipmentMethodTransfer1 = $this->tester->haveShipmentMethod();
        $shipmentMethodTransfer2 = $this->tester->haveShipmentMethod();
        $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())
            ->addMethod($shipmentMethodTransfer1)
            ->addMethod($shipmentMethodTransfer2);

        $storeTransferDe = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAt = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransferDe),
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransferAt),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer1->getIdShipmentTypeOrFail(),
        );
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer2->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer2->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransferDe);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem([ItemTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer1])
            ->withAnotherItem([ItemTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer2])
            ->withAvailableShipmentMethods($shipmentMethodsTransfer->toArray())
            ->build();

        // Act
        $shipmentMethodTransfers = $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $this->assertSame(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfers->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testCorrectlyFiltersOutMultipleShipmentMethods(): void
    {
        // Arrange
        $shipmentMethodTransfer1 = $this->tester->haveShipmentMethod();
        $shipmentMethodTransfer2 = $this->tester->haveShipmentMethod();
        $shipmentMethodTransfer3 = $this->tester->haveShipmentMethod();
        $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())
            ->addMethod($shipmentMethodTransfer1)
            ->addMethod($shipmentMethodTransfer2)
            ->addMethod($shipmentMethodTransfer3);

        $storeTransfer = $this->tester->haveStore();
        $activeShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $inactiveShipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $activeShipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer2->getIdShipmentMethodOrFail(),
            $inactiveShipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer3->getIdShipmentMethodOrFail(),
            $inactiveShipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem([ItemTransfer::SHIPMENT_TYPE => $activeShipmentTypeTransfer])
            ->withAvailableShipmentMethods($shipmentMethodsTransfer->toArray())
            ->build();

        // Act
        $shipmentMethodTransfers = $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);

        // Assert
        $this->assertCount(1, $shipmentMethodTransfers);
        $this->assertSame(
            $shipmentMethodTransfer1->getIdShipmentMethodOrFail(),
            $shipmentMethodTransfers->getIterator()->current()->getIdShipmentMethod(),
        );
    }

    /**
     * @dataProvider throwsExceptionWhenRequiredTransferPropertyIsNotProvidedDataProvider
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredTransferPropertyIsNotProvided(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenAvailableShipmentMethodsTransferPropertyIsNotProvided(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withItem([ItemTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer])
            ->build();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenIdShipmentMethodTransferPropertyIsNotProvided(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $storeTransfer = $this->tester->haveStore();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer);
        $shipmentGroupTransfer = (new ShipmentGroupBuilder())
            ->withAvailableShipmentMethods(
                (new ShipmentMethodsBuilder())->withMethod([ShipmentMethodTransfer::ID_SHIPMENT_METHOD => null]),
            )
            ->withItem([ItemTransfer::SHIPMENT_TYPE => $shipmentTypeTransfer])
            ->build();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->filterShipmentGroupMethods($shipmentGroupTransfer, $quoteTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\ShipmentGroupTransfer|\Generated\Shared\Transfer\QuoteTransfer>>
     */
    protected function throwsExceptionWhenRequiredTransferPropertyIsNotProvidedDataProvider(): array
    {
        return [
            'QuoteTransfer.stores is missing' => [
                (new ShipmentGroupBuilder())
                    ->withItem((new ItemBuilder())->withShipmentType([ShipmentTypeTransfer::UUID => 'shipment-type-uuid']))
                    ->withAvailableShipmentMethods((new ShipmentMethodsBuilder())->withMethod([ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1]))
                    ->build(),
                (new QuoteBuilder([QuoteTransfer::STORE => null]))->build(),
            ],
            'QuoteTransfer.stores.name is missing' => [
                (new ShipmentGroupBuilder())
                    ->withItem((new ItemBuilder())->withShipmentType([ShipmentTypeTransfer::UUID => 'shipment-type-uuid']))
                    ->withAvailableShipmentMethods((new ShipmentMethodsBuilder())->withMethod([ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1]))
                    ->build(),
                (new QuoteBuilder())->withStore([StoreTransfer::NAME => null])->build(),
            ],
            'ShipmentGroupTransfer.items.shipmentType.uuid is missing' => [
                (new ShipmentGroupBuilder())
                    ->withItem((new ItemBuilder())->withShipmentType([ShipmentTypeTransfer::UUID => null]))
                    ->withAvailableShipmentMethods((new ShipmentMethodsBuilder())->withMethod([ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1]))
                    ->build(),
                (new QuoteBuilder())->withStore([StoreTransfer::NAME => static::STORE_NAME_DE])->build(),
            ],
        ];
    }
}
