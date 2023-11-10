<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\WarehouseUserAssignmentBuilder;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use SprykerTest\Zed\WarehouseUser\WarehouseUserBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group WarehouseUser
 * @group Business
 * @group Facade
 * @group Facade
 * @group CreateWarehouseUserAssignmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class CreateWarehouseUserAssignmentCollectionFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_USER_UUID = 'fake-user-uuid';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\UserExistsValidatorRule::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND = 'warehouse_user_assignment.validation.user_not_found';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseExistsValidatorRule::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND = 'warehouse_user_assignment.validation.warehouse_not_found';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\SingleActiveWarehouseUserAssignmentValidatorRule::GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS = 'warehouse_user_assignment.validation.too_many_active_warehouse_assignments';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentAlreadyExistsValidatorRule::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS = 'warehouse_user_assignment.validation.warehouse_user_assignment_already_exists';

    /**
     * @var \SprykerTest\Zed\WarehouseUser\WarehouseUserBusinessTester
     */
    protected WarehouseUserBusinessTester $tester;

    /**
     * @return void
     */
    public function testPersistsWarehouseUserAssignment(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder())->build()
            ->setWarehouse($stockTransfer)
            ->setUserUuid($userTransfer->getUuidOrFail());
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $warehouseUserAssignmentTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments()->getIterator()->current();
        $this->assertNotNull($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignment());
        $this->assertNotNull($warehouseUserAssignmentTransfer->getUuid());
        $this->assertNotNull($warehouseUserAssignmentTransfer->getWarehouse());
        $this->assertSame($userTransfer->getUuidOrFail(), $warehouseUserAssignmentTransfer->getUserUuid());
        $this->assertSame($stockTransfer->getIdStockOrFail(), $warehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStock());
    }

    /**
     * @return void
     */
    public function testDoesNotPersistWarehouseUserAssignmentWhenValidationReturnsError(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder())->build()
            ->setWarehouse($stockTransfer)
            ->setUserUuid(static::FAKE_USER_UUID);
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $warehouseUserAssignmentTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments()->getIterator()->current();
        $this->assertNull($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignment());
        $this->assertNull($warehouseUserAssignmentTransfer->getUuid());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenUserNotExist(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder())->build()
            ->setWarehouse($stockTransfer)
            ->setUserUuid(static::FAKE_USER_UUID);
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhileUserIsNotAWarehouseUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => false]);
        $stockTransfer = $this->tester->haveStock();

        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder())->build()
            ->setWarehouse($stockTransfer)
            ->setUserUuid($userTransfer->getUuidOrFail());

        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()
            ->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenWarehouseNotExist(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->getNotExistingStockTransfer();
        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder())->build()
            ->setWarehouse($stockTransfer)
            ->setUserUuid($userTransfer->getUuidOrFail());
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenWarehouseWithIncorrectUuidWithoutIdStockIsProvided(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->getNotExistingStockTransfer();
        $stockTransfer->setIdStock(null);

        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder())->build()
            ->setWarehouse($stockTransfer)
            ->setUserUuid($userTransfer->getUuidOrFail());
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenRequestContainsMoreThanOneActiveWarehouseUserAssignment(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer1 = $this->tester->haveStock();
        $stockTransfer2 = $this->tester->haveStock();

        $warehouseUserAssignmentTransfer1 = (new WarehouseUserAssignmentBuilder([
            WarehouseUserAssignmentTransfer::USER_UUID => $userTransfer->getUuidOrFail(),
            WarehouseUserAssignmentTransfer::IS_ACTIVE => true,
        ]))->build()->setWarehouse($stockTransfer1);
        $warehouseUserAssignmentTransfer2 = (new WarehouseUserAssignmentBuilder([
            WarehouseUserAssignmentTransfer::USER_UUID => $userTransfer->getUuidOrFail(),
            WarehouseUserAssignmentTransfer::IS_ACTIVE => true,
        ]))->build()->setWarehouse($stockTransfer2);
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($warehouseUserAssignmentTransfer1)
            ->addWarehouseUserAssignment($warehouseUserAssignmentTransfer2);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(2, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfersIterator = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator();
        $errorTransfer1 = $errorTransfersIterator->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS, $errorTransfer1->getMessage());
        $errorTransfersIterator->next();
        $errorTransfer2 = $errorTransfersIterator->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS, $errorTransfer2->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenWarehouseUserAssignmentAlreadyExists(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );

        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder())->build()
            ->setWarehouse($stockTransfer)
            ->setUserUuid($userTransfer->getUuidOrFail());
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testDeactivatesCurrentlyActiveWarehouseUserAssignmentWhenCreatingActiveWarehouseUserAssignment(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $stockTransfer = $this->tester->haveStock();
        $activeWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => true],
        );
        $newWarehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder([
            WarehouseUserAssignmentTransfer::USER_UUID => $userTransfer->getUuidOrFail(),
            WarehouseUserAssignmentTransfer::IS_ACTIVE => true,
        ]))->build()->setWarehouse($stockTransfer);
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($newWarehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $inactiveWarehouseUserAssignmentEntity = $this->tester->findWarehouseUserAssignment($activeWarehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
        $this->assertNotNull($inactiveWarehouseUserAssignmentEntity);
        $this->assertFalse($inactiveWarehouseUserAssignmentEntity->getIsActive());

        $activeWarehouseUserAssignmentEntity = $this->tester->findWarehouseUserAssignment($newWarehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
        $this->assertNotNull($activeWarehouseUserAssignmentEntity);
        $this->assertTrue($activeWarehouseUserAssignmentEntity->getIsActive());
        $this->assertSame($newWarehouseUserAssignmentTransfer->getUserUuidOrFail(), $activeWarehouseUserAssignmentEntity->getUserUuid());
        $this->assertSame($newWarehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStockOrFail(), $activeWarehouseUserAssignmentEntity->getFkWarehouse());
    }

    /**
     * @return void
     */
    public function testShouldNotReturnErrorWhenIsActiveIsNotProvided(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::IS_WAREHOUSE_USER => true]);
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => true],
        );
        $warehouseUserAssignmentWithEmptyIsActiveTransfer = (new WarehouseUserAssignmentBuilder([
            WarehouseUserAssignmentTransfer::USER_UUID => $userTransfer->getUuidOrFail(),
            WarehouseUserAssignmentTransfer::IS_ACTIVE => null,
        ]))->build()->setWarehouse($this->tester->haveStock());

        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($warehouseUserAssignmentWithEmptyIsActiveTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()
            ->createWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $oldWarehouseUserAssignmentTransfer = $this->tester->findWarehouseUserAssignment($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
        $this->assertNotNull($oldWarehouseUserAssignmentTransfer);
        $this->assertTrue($oldWarehouseUserAssignmentTransfer->getIsActive());

        $newWarehouseUserAssignmentTransfer = $this->tester->findWarehouseUserAssignment($warehouseUserAssignmentWithEmptyIsActiveTransfer->getIdWarehouseUserAssignmentOrFail());
        $this->assertNotNull($newWarehouseUserAssignmentTransfer);
        $this->assertFalse($newWarehouseUserAssignmentTransfer->getIsActive());
    }
}
