<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PickingList\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\PickingList\Business\Validator\PickingListValidatorCompositeRuleInterface;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface;
use SprykerTest\Zed\PickingList\PickingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingList
 * @group Business
 * @group Facade
 * @group UpdatePickingListCollectionFacadeTest
 * Add your own group annotations below this line
 */
class UpdatePickingListCollectionFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_UUID = 'ebad5042-0db1-498e-9981-42f45f98ad3d';

    /**
     * @uses \Spryker\Shared\PickingList\PickingListConfig::STATUS_PICKING_FINISHED
     *
     * @var string
     */
    public const STATUS_PICKING_FINISHED = 'picking-finished';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListExistsPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND = 'picking_list.validation.picking_list_entity_not_found';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemExistsPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND = 'picking_list.validation.picking_list_item_entity_not_found';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_PROPERTY_PICKING_LIST_ITEM_QUANTITY = 'picking_list.validation.wrong_property_picking_list_item_quantity';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY = 'picking_list.validation.incorrect_quantity';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemUpdateQuantityIncorrectPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED = 'picking_list.validation.only_full_quantity_picking_allowed';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListPickedByAnotherUserPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER = 'picking_list.validation.picked_by_another_user';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingList\PickingListDuplicatedPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_PICKING_LIST_DUPLICATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKING_LIST_DUPLICATED = 'picking_list.validation.picking_list_duplicated';

    /**
     * @uses \Spryker\Zed\PickingList\Business\Validator\Rules\PickingListItem\PickingListItemDuplicatedPickingListValidatorCompositeRule::GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED = 'picking_list.validation.picking_list_item_duplicated';

    /**
     * @var \SprykerTest\Zed\PickingList\PickingListBusinessTester
     */
    protected PickingListBusinessTester $tester;

    /**
     * @dataProvider getUpdatePickingListCollectionDataProvider
     *
     * @param int $numberOfItems
     *
     * @return void
     */
    public function testUpdatePickingListCollection(int $numberOfItems): void
    {
        // Arrange
        $pickingListItemTransferCollection = [];
        for ($i = 0; $i < $numberOfItems; $i++) {
            $pickingListItemTransferCollection[] = $this->tester->createPickingListItemTransferWithOrder();
        }

        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment($pickingListItemTransferCollection);

        // 1
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
        $this->assertPickingListCollectionResponseContainsTransferWithId(
            $pickingListCollectionResponseTransfer,
            $pickingListTransfer,
            $pickingListTransfer->getUuid(),
            1,
        );
        $this->assertPickingListCollectionResponseContainsPickingListItemTransfers($pickingListCollectionResponseTransfer, $numberOfItems);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnCollectionWithOnePickingListEntityWhenEntityWasSavedNonTransactional(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([]);

        // 1
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(false);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
        $this->assertPickingListCollectionResponseContainsTransferWithId(
            $pickingListCollectionResponseTransfer,
            $pickingListTransfer,
            $pickingListTransfer->getUuid(),
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnCollectionWithOnePickingListEntityAndTwoPickingListItemEntitiesWhenEntityWasSavedNonTransactional(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $this->tester->createPickingListItemTransferWithOrder(),
            $this->tester->createPickingListItemTransferWithOrder(),
        ]);

        // 2
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(false);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
        $this->assertPickingListCollectionResponseContainsTransferWithId(
            $pickingListCollectionResponseTransfer,
            $pickingListTransfer,
            $pickingListTransfer->getUuid(),
            1,
        );
        $this->assertPickingListCollectionResponseContainsPickingListItemTransfers($pickingListCollectionResponseTransfer, 2);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionAppliesPostUpdatePlugins(): void
    {
        // Arrange
        $this->havePickingListPostUpdatePluginSetUuidTwoEnabled();

        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([]);
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
        $this->assertPickingListCollectionResponseContainsTransferWithId(
            $pickingListCollectionResponseTransfer,
            $pickingListTransfer,
            static::FAKE_UUID,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWhenValidationRuleFailed(): void
    {
        // Arrange
        $this->mockPickingListAlwaysFailingValidatorRule();
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($this->tester->createPickingListTransfer())
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

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
    public function testUpdatePickingListCollectionShouldThrowExceptionWhenIsTransactionalNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingListTransfer();
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::IS_TRANSACTIONAL);

        // Act
        $this->tester->getFacade()->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldThrowExceptionWhenPickingListIsEmpty(): void
    {
        // Arrange
        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::class);
        $this->expectExceptionMessage(PickingListCollectionRequestTransfer::PICKING_LISTS);

        // Act
        $this->tester->getFacade()->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldThrowExceptionWhenPickingListUuidNotSet(): void
    {
        // Arrange
        $pickingListTransfer1 = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([])
            ->setUuid(null);

        $pickingListTransfer2 = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer1)
            ->addPickingList($pickingListTransfer2)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListTransfer::class);
        $this->expectExceptionMessage(PickingListTransfer::UUID);

        // Act
        $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListUuidIsWrong(): void
    {
        // Arrange
        $pickingListTransfer1 = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([])
            ->setUuid(static::FAKE_UUID);

        $pickingListTransfer2 = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer1)
            ->addPickingList($pickingListTransfer2)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldThrowExceptionWhenPickingListItemUuidNotSet(): void
    {
        // Arrange
        $pickingListTransfer1 = $this->createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutUuid();
        $pickingListTransfer2 = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $this->tester->createPickingListItemTransferWithOrder(),
        ]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer1)
            ->addPickingList($pickingListTransfer2)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::UUID);

        // Act
        $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListItemUuidIsWrong(): void
    {
        // Arrange
        $pickingListTransfer1 = $this->createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithWrongUuid();
        $pickingListTransfer2 = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $this->tester->createPickingListItemTransferWithOrder(),
        ]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer1)
            ->addPickingList($pickingListTransfer2)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListEntityNotFound(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
        ]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListItemEntityNotFound(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $this->tester->createPickingListItemTransferWithOrder(),
        ]);
        $pickingListTransfer->addPickingListItem(
            $this->tester->createPickingListItemTransferWithOrder(),
        );

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_ITEM_ENTITY_NOT_FOUND,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldThrowExceptionWhenPickingListItemQuantityIsNull(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutQuantity();

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::QUANTITY);

        // Act
        $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListItemQuantityIsZero(): void
    {
        // Arrange
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        $pickingListTransfer = $pickingListTransfer
            ->setPickingListItems(new ArrayObject())
            ->addPickingListItem($pickingListItemTransfer->setQuantity(0));

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

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
    public function testUpdatePickingListCollectionShouldThrowExceptionWhenPickingListItemNumberOfPickedNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutNumberOfPicked();

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::NUMBER_OF_PICKED);

        // Act
        $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldThrowExceptionWhenPickingListItemNumberOfNotPickedNotSet(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutNumberOfNotPicked();

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage(PickingListItemTransfer::class);
        $this->expectExceptionMessage(PickingListItemTransfer::NUMBER_OF_NOT_PICKED);

        // Act
        $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingFinishedAndPickingListProcessedQuantityIsZero(): void
    {
        // Arrange
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        $pickingListItemTransfer->setNumberOfPicked(0)
            ->setNumberOfNotPicked(0);

        $pickingListTransfer->setStatus(static::STATUS_PICKING_FINISHED)
            ->setPickingListItems(
                new ArrayObject([$pickingListItemTransfer]),
            );

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_INCORRECT_QUANTITY,
            1,
        );
    }

    /**
     * @dataProvider getUpdatePickingListCollectionErrorDataProvider
     *
     * @param array<string, mixed> $pickingListUpdateData
     *
     * @return void
     */
    public function testUpdatePickingListCollectionError(array $pickingListUpdateData): void
    {
        // Arrange
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        $pickingListItemTransfer->fromArray($pickingListUpdateData);

        $pickingListTransfer->setPickingListItems(
            new ArrayObject([$pickingListItemTransfer]),
        );

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_ONLY_FULL_QUANTITY_ALLOWED,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnCollectionWithOnePickingListEntityWhenPickingListItemOrderNotSet(): void
    {
        // Arrange
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);
        $pickingListItemTransfer->setOrderItem(null);
        $pickingListTransfer->setPickingListItems(
            new ArrayObject([$pickingListItemTransfer]),
        );

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(false);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($pickingListCollectionResponseTransfer->getErrors());
        $this->assertPickingListCollectionResponseContainsTransferWithId(
            $pickingListCollectionResponseTransfer,
            $pickingListTransfer,
            $pickingListTransfer->getUuid(),
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithTwoErrorsWhenPickingListUserUuidIsChanged(): void
    {
        // Arrange
        $warehouseTransfer = $this->tester->haveStock();
        $userTransfer = $this->tester->haveUser();
        $this->tester->haveWarehouseUserAssignment($userTransfer, $warehouseTransfer);

        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $warehouseTransfer,
            PickingListTransfer::USER => $userTransfer,
        ]);
        $pickingListTransfer = $this->tester->havePickingList($pickingListTransfer);

        $pickingListTransfer->setUser(
            $this->tester->haveUser(),
        );

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_PICKED_BY_ANOTHER_USER,
            2,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListDuplicated(): void
    {
        // Arrange
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_PICKING_LIST_DUPLICATED,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListItemDuplicated(): void
    {
        // Arrange
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer->addPickingListItem($pickingListItemTransfer))
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_PICKING_LIST_ITEM_DUPLICATED,
            1,
        );
    }

    /**
     * @return void
     */
    public function testUpdatePickingListCollectionShouldReturnErroredCollectionResponseWithOneErrorWhenPickingListUserHasNoWarehouseUserAssignment(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::USER => $this->tester->haveUser(),
        ]);
        $pickingListTransfer = $this->tester->havePickingList($pickingListTransfer);

        $pickingListCollectionRequestTransfer = (new PickingListCollectionRequestTransfer())
            ->addPickingList($pickingListTransfer)
            ->setIsTransactional(true);

        // Act
        $pickingListCollectionResponseTransfer = $this->tester->getFacade()
            ->updatePickingListCollection($pickingListCollectionRequestTransfer);

        // Assert
        $this->assertPickingListCollectionResponseContainsFailedValidationRuleError(
            $pickingListCollectionResponseTransfer,
            static::GLOSSARY_KEY_VALIDATION_ENTITY_NOT_FOUND,
            1,
        );
    }

    /**
     * @return array<string, array<array<string, mixed>>>
     */
    protected function getUpdatePickingListCollectionErrorDataProvider(): array
    {
        return [
            'Should return errored collection response with one error when picking list quantity not same as picking list processed quantity' => [
                [
                    PickingListItemTransfer::NUMBER_OF_PICKED => rand(1, 50),
                    PickingListItemTransfer::QUANTITY => rand(51, 100),
                ],
            ],
            'Should return errored collection response with one error when number of picked not zero and number of not picked not zero' => [
                [
                    PickingListItemTransfer::NUMBER_OF_PICKED => rand(1, 50),
                    PickingListItemTransfer::NUMBER_OF_NOT_PICKED => rand(1, 50),
                    PickingListItemTransfer::QUANTITY => rand(51, 100),
                ],
            ],
        ];
    }

    /**
     * @return array<string, list<int>>
     */
    protected function getUpdatePickingListCollectionDataProvider(): array
    {
        return [
            'Should return collection with one picking list entity when entity was saved transactional' => [0],
            'Should return collection with one picking list entity and two picking list item entities when entity was saved transactional' => [2],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param string $uuid
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertPickingListCollectionResponseContainsTransferWithId(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer,
        PickingListTransfer $pickingListTransfer,
        string $uuid,
        int $expectedCount
    ): void {
        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionResponseTransfer->getPickingLists();
        $updatedPickingListTransfer = $pickingListTransferCollection->getIterator()->current() ?? new PickingListTransfer();

        $this->assertCount($expectedCount, $pickingListCollectionResponseTransfer->getPickingLists());
        $this->assertEquals($uuid, $updatedPickingListTransfer->getUuid());
        $this->assertEquals($pickingListTransfer->getIdPickingList(), $updatedPickingListTransfer->getIdPickingList());
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     * @param int $expectedNumberOfItems
     *
     * @return void
     */
    protected function assertPickingListCollectionResponseContainsPickingListItemTransfers(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer,
        int $expectedNumberOfItems
    ): void {
        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransferCollection */
        $pickingListTransferCollection = $pickingListCollectionResponseTransfer->getPickingLists();
        $pickingListTransfer = $pickingListTransferCollection->getIterator()->current() ?? new PickingListTransfer();

        $this->assertCount($expectedNumberOfItems, $pickingListTransfer->getPickingListItems());
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
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransferCollection
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment(
        array $pickingListItemTransferCollection
    ): PickingListTransfer {
        $userTransfer = $this->tester->haveUser();
        $warehouseTransfer = $this->tester->haveStock();

        $this->tester->haveWarehouseUserAssignment($userTransfer, $warehouseTransfer);

        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::USER => $userTransfer,
            PickingListTransfer::WAREHOUSE => $warehouseTransfer,
            PickingListTransfer::PICKING_LIST_ITEMS => $pickingListItemTransferCollection,
        ]);

        return $this->tester->havePickingList($pickingListTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutQuantity(): PickingListTransfer
    {
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        return $pickingListTransfer
            ->setPickingListItems(new ArrayObject())
            ->addPickingListItem($pickingListItemTransfer->setQuantity(null));
    }

    /**
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutUuid(): PickingListTransfer
    {
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        return $pickingListTransfer
            ->setPickingListItems(new ArrayObject())
            ->addPickingListItem($pickingListItemTransfer->setUuid(null));
    }

    /**
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithWrongUuid(): PickingListTransfer
    {
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        return $pickingListTransfer
            ->setPickingListItems(new ArrayObject())
            ->addPickingListItem($pickingListItemTransfer->setUuid(static::FAKE_UUID));
    }

    /**
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutNumberOfPicked(): PickingListTransfer
    {
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        return $pickingListTransfer
            ->setPickingListItems(new ArrayObject())
            ->addPickingListItem($pickingListItemTransfer->setNumberOfPicked(null));
    }

    /**
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransferPersistedWithWarehouseAndOnePickingListItemWithoutNumberOfNotPicked(): PickingListTransfer
    {
        $pickingListItemTransfer = $this->tester->createPickingListItemTransferWithOrder();
        $pickingListTransfer = $this->createPickingListTransferPersistedWithWarehousePickingListItemsAndWarehouseUserAssignment([
            $pickingListItemTransfer,
        ]);

        return $pickingListTransfer
            ->setPickingListItems(new ArrayObject())
            ->addPickingListItem($pickingListItemTransfer->setNumberOfNotPicked(null));
    }

    /**
     * @return void
     */
    protected function mockPickingListAlwaysFailingValidatorRule(): void
    {
        $pickingListValidatorRule = new class implements PickingListValidatorCompositeRuleInterface {
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
            'getUpdatePickingListValidatorCompositeRules',
            [$pickingListValidatorRule],
        );
    }

    /**
     * @return void
     */
    protected function havePickingListPostUpdatePluginSetUuidTwoEnabled(): void
    {
        $this->tester->mockFactoryMethod(
            'getPickingListPostUpdatePlugins',
            [
                $this->mockUpdatePlugin(static::FAKE_UUID),
            ],
        );
    }

    /**
     * @param string $uuid
     *
     * @return \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface
     */
    protected function mockUpdatePlugin(string $uuid): PickingListPostUpdatePluginInterface
    {
        return new class ($uuid) implements PickingListPostUpdatePluginInterface {
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
            public function postUpdate(PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer): PickingListCollectionResponseTransfer
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
