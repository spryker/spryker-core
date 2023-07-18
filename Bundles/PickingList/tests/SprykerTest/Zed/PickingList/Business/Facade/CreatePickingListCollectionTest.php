<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface;
use SprykerTest\Zed\PickingList\PickingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingList
 * @group Business
 * @group Facade
 * @group CreatePickingListCollectionTest
 * Add your own group annotations below this line
 */
class CreatePickingListCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_UUID = 'ebad5042-0db1-498e-9981-42f45f98ad3d';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED = 'picking_list.validation.wrong_property_picking_list_item_number_of_picked';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED = 'picking_list.validation.wrong_property_picking_list_item_number_of_not_picked';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemCreateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY = 'picking_list.validation.wrong_property_picking_list_item_quantity';

    /**
     * @var \SprykerTest\Zed\PickingList\PickingListBusinessTester
     */
    protected PickingListBusinessTester $tester;

    /**
     * @dataProvider getCreatePickingListCollectionDataProvider
     *
     * @param array<int, array<string, mixed> $pickingListItemsData
     * @param bool $isTransactional
     * @param int $expectedPickingListItemCount
     *
     * @return void
     */
    public function testCreatePickingListCollection(
        array $pickingListItemsData,
        bool $isTransactional,
        int $expectedPickingListItemCount
    ): void {
        // Arrange
        $pickingListItemTransferCollection = [];
        foreach ($pickingListItemsData as $pickingListItemData) {
            $pickingListItemTransferCollection[] = $this->tester->createPickingListItemTransferWithOrder($pickingListItemData);
        }

        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems($pickingListItemTransferCollection);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional($isTransactional);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionResponseTransfer->getPickingLists();
        $updatedPickingListTransfer = $pickingListTransferCollection->getIterator()->current();

        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $pickingListTransferCollection);
        $this->assertNotNull($updatedPickingListTransfer->getIdPickingList());
        $this->assertEquals(
            $pickingListTransfer->getUuid(),
            $updatedPickingListTransfer->getUuid(),
        );
        $this->assertCount($expectedPickingListItemCount, $updatedPickingListTransfer->getPickingListItems());
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldReturnCollectionWithOnePickingListEntityWhenEntityWasSavedNonTransactional(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(false);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionResponseTransfer->getPickingLists();

        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $pickingListTransferCollection);
        $this->assertNotNull($pickingListTransferCollection->getIterator()->current()->getIdPickingList());
        $this->assertEquals(
            $pickingListTransfer->getUuid(),
            $pickingListTransferCollection->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionAppliesPostCreatePlugins(): void
    {
        // Arrange
        $this->havePickingListPostCreatePluginSetUuidTwoEnabled();

        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListTransferHasFakeUuid($pickingListCollectionResponseTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldReturnErroredCollectionResponseWhenValidationRuleFailed(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([]);

        $this->mockPickingListAlwaysFailingValidatorRule();
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            'Validation failed',
            1,
        );
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenIsTransactionalNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::IS_TRANSACTIONAL);

        // Act
        $this->tester->getFacade()->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenPickingListIsEmpty(): void
    {
        // Arrange
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::PICKING_LISTS);

        // Act
        $this->tester->getFacade()->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenPickingListWarehouseNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([]);
        $pickingListTransfer->setWarehouse();

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListTransfer::class);
        $this->expectExceptionMessage(PickingListTransfer::WAREHOUSE);

        // Act
        $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenIdWarehouseNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([]);
        $pickingListTransfer->getWarehouse()->setIdStock(null);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(StockTransfer::class);
        $this->expectExceptionMessage(StockTransfer::ID_STOCK);

        // Act
        $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenPickingListItemQuantityNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder()->setQuantity(null),
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::QUANTITY);

        // Act
        $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListItemQuantityIsZero(): void
    {
        // Arrange
        $pickingListTransfer1 = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder()->setQuantity(0),
        ]);
        $pickingListTransfer2 = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder(),
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer1)
            ->addPickingList($pickingListTransfer2)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY,
            1,
        );
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenPickingListItemNumberOfPickedNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder()->setNumberOfPicked(null),
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::NUMBER_OF_PICKED);

        // Act
        $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListItemNumberOfPickedIsNotZero(): void
    {
        // Arrange
        $pickingListTransfer1 = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder()->setNumberOfPicked(rand()),
        ]);
        $pickingListTransfer2 = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder(),
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer1)
            ->addPickingList($pickingListTransfer2)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_PICKED,
            1,
        );
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenPickingListItemNumberOfNotPickedNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder()->setNumberOfNotPicked(null),
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::NUMBER_OF_NOT_PICKED);

        // Act
        $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListItemNumberOfNotPickedIsNotZero(): void
    {
        // Arrange
        $pickingListTransfer1 = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder()->setNumberOfNotPicked(rand()),
        ]);
        $pickingListTransfer2 = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder(),
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer1)
            ->addPickingList($pickingListTransfer2)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_NUMBER_OF_NOT_PICKED,
            1,
        );
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenPickingListItemOrderItemNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $this->tester->createPickingListItemTransferWithOrder()->setOrderItem(null),
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::ORDER_ITEM);

        // Act
        $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePickingListCollectionShouldThrowExceptionWhenPickingListItemOrderItemUuidNotSet(): void
    {
        // Arrange
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListItemTransfer->getOrderItem()->setUuid(null);
        $pickingListTransfer = $this->createPickingListTransferWithWarehouseAndPickingListItems([
            $pickingListItemTransfer,
        ]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(ItemTransfer::class);
        $this->expectExceptionMessage(ItemTransfer::UUID);

        // Act
        $this->tester->getFacade()
            ->createPickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return array<string, array<array<string, mixed>>|bool|int>
     */
    protected function getCreatePickingListCollectionDataProvider(): array
    {
        return [
            'Should return collection with one picking list entity when entity was saved transactional' => [[], true, 0],
            'Should return collection with one picking list entity when picking list item number of picked is zero' => [
                [[
                    PickingListItemTransfer::NUMBER_OF_PICKED => 0,
                ]],
                true,
                1,
            ],
            'Should return collection with one picking list entity when picking list item number of not picked is zero' => [
                [[
                    PickingListItemTransfer::NUMBER_OF_NOT_PICKED => 0,
                ]],
                true,
                1,
            ],
            'Should return collection with one picking list entity and two picking list item entities when entity was saved transactional' => [
                [
                    [
                        PickingListItemTransfer::QUANTITY => 1,
                    ],
                    [
                        PickingListItemTransfer::QUANTITY => 1,
                    ],
                ],
                true,
                2,
            ],
            'Should return collection with one picking list entity and two picking list item entities when entity was saved non transactional' => [
                [
                    [
                        PickingListItemTransfer::QUANTITY => 1,
                    ],
                    [
                        PickingListItemTransfer::QUANTITY => 1,
                    ],
                ],
                false,
                2,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     * @param string $message
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertPickingListCollectionResponseContainsFailedValidationRuleError(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer,
        string $message,
        int $expectedCount
    ): void {
        $errorFound = false;
        foreach ($pickingListCollectionResponseTransfer->getErrors() as $errorTransfer) {
            if (strstr($errorTransfer->getMessage(), $message) !== false) {
                $errorFound = true;
            }
        }

        $this->assertCount($expectedCount, $pickingListCollectionResponseTransfer->getErrors());
        $this->assertTrue(
            $errorFound,
            sprintf('Expected to have a message "%s" in the error collection but was not found', $message),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return void
     */
    protected function assertPickingListTransferHasFakeUuid(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): void {
        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionResponseTransfer->getPickingLists();

        $this->assertCount(1, $pickingListTransferCollection);
        $this->assertEquals(static::FAKE_UUID, $pickingListTransferCollection->getIterator()->current()->getUuid());
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return void
     */
    protected function assertPickingListCollectionContainsTransferWithId(
        ArrayObject $pickingListTransferCollection,
        PickingListTransfer $pickingListTransfer
    ): void {
        $transferFound = false;

        foreach ($pickingListTransferCollection as $pickingListTransferFromCollection) {
            if ($pickingListTransferFromCollection->getUuid() === $pickingListTransfer->getUuid()) {
                $transferFound = true;
            }
        }

        $this->assertTrue(
            $transferFound,
            sprintf(
                'Expected to have a transfer in the collection but transfer by uuid "%s" was not found in the collection',
                $pickingListTransfer->getUuid(),
            ),
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransferCollection
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransferWithWarehouseAndPickingListItems(
        array $pickingListItemTransferCollection
    ): PickingListTransfer {
        return $this->tester->createPickingListTransfer([
            PickingListTransfer::USER => null,
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => $pickingListItemTransferCollection,
        ]);
    }

    /**
     * @return void
     */
    protected function mockPickingListAlwaysFailingValidatorRule(): void
    {
        $pickingListValidatorCompositeRule = new class implements PickingListValidatorCompositeRuleInterface {
            /**
             * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
             * @param array<string, \Generated\Shared\Transfer\PickingListTransfer> $existingPickingListTransferCollectionIndexedByUuid
             * @param array<string, \Generated\Shared\Transfer\PickingListItemTransfer> $existingPickingListItemTransferCollectionIndexedByUuid
             *
             * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
             */
            public function validate(
                PickingListCollectionTransfer $pickingListCollectionTransfer,
                array $existingPickingListTransferCollectionIndexedByUuid,
                array $existingPickingListItemTransferCollectionIndexedByUuid
            ): ErrorCollectionTransfer {
                return (new ErrorCollectionTransfer())->addError(
                    (new ErrorTransfer())->setMessage('Validation failed'),
                );
            }
        };

        $this->tester->mockFactoryMethod(
            'getCreatePickingListValidatorCompositeRules',
            [$pickingListValidatorCompositeRule],
        );
    }

    /**
     * @return void
     */
    protected function havePickingListPostCreatePluginSetUuidTwoEnabled(): void
    {
        $this->tester->mockFactoryMethod(
            'getPickingListPostCreatePlugins',
            [
                $this->mockCreatePlugin(static::FAKE_UUID),
            ],
        );
    }

    /**
     * @param string $uuid
     *
     * @return \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface
     */
    protected function mockCreatePlugin(string $uuid): PickingListPostCreatePluginInterface
    {
        return new class ($uuid) implements PickingListPostCreatePluginInterface {
            /**
             * @var string
             */
            private $uuid;

            /**
             * @param string $uuid
             */
            public function __construct(string $uuid)
            {
                $this->uuid = $uuid;
            }

            /**
             * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
             *
             * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
             */
            public function postCreate(PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer): PickingListCollectionResponseTransfer
            {
                foreach ($pickingListCollectionResponseTransfer->getPickingLists() as $pickingListTransfer) {
                    $pickingListTransfer->setUuid($this->uuid);
                }

                return (new PickingListCollectionResponseTransfer())
                    ->setPickingLists($pickingListCollectionResponseTransfer->getPickingLists());
            }
        };
    }
}
