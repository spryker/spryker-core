<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUser\Business\Facade;

use Codeception\Test\Unit;
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
 * @group UpdateWarehouseUserAssignmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class UpdateWarehouseUserAssignmentCollectionFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_USER_UUID = 'fake-user-uuid';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentExistsValidatorRule::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND = 'warehouse_user_assignment.validation.warehouse_user_assignment_not_found';

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
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );
        $warehouseUserAssignmentTransfer->setIsActive(true);
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $updatedWarehouseUserAssignmentTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments()->getIterator()->current();
        $this->assertSame(
            $warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail(),
            $updatedWarehouseUserAssignmentTransfer->getIdWarehouseUserAssignment(),
        );
        $this->assertSame(
            $warehouseUserAssignmentTransfer->getUuidOrFail(),
            $updatedWarehouseUserAssignmentTransfer->getUuid(),
        );
        $this->assertTrue($updatedWarehouseUserAssignmentTransfer->getIsActive());
        $this->assertNotNull($updatedWarehouseUserAssignmentTransfer->getWarehouse());
        $this->assertSame($userTransfer->getUuidOrFail(), $updatedWarehouseUserAssignmentTransfer->getUserUuid());
        $this->assertSame($stockTransfer->getIdStockOrFail(), $updatedWarehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStock());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenWarehouseUserAssignmentNotExist(): void
    {
        // Arrange
        $warehouseUserAssignmentTransfer = $this->tester->getNotExistingWarehouseUserAssignmentTransfer();
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenUserDoesNotExist(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );
        $warehouseUserAssignmentTransfer->setUserUuid(static::FAKE_USER_UUID);
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenWarehouseDoesNotExist(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );
        $warehouseUserAssignmentTransfer->setWarehouse($this->tester->getNotExistingStockTransfer());
        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $errorTransfer = $warehouseUserAssignmentCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND, $errorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenActiveWarehouseUserAssignmentNotChanged(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => true],
        );

        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenActiveWarehouseUserAssignmentChanged(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $activeWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => true],
        );
        $inactiveWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );

        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($activeWarehouseUserAssignmentTransfer->setIsActive(false))
            ->addWarehouseUserAssignment($inactiveWarehouseUserAssignmentTransfer->setIsActive(true));

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenRequestContainsMoreThanOneActiveWarehouseUserAssignment(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $activeWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );
        $inactiveWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => false],
        );

        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($activeWarehouseUserAssignmentTransfer->setIsActive(true))
            ->addWarehouseUserAssignment($inactiveWarehouseUserAssignmentTransfer->setIsActive(true));

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
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
    public function testDeactivatesCurrentlyActiveWarehouseUserAssignmentWhenUpdatedWarehouseUserAssignmentIsActive(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $activeWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [WarehouseUserAssignmentTransfer::IS_ACTIVE => true],
        );
        $inactiveWarehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $this->tester->haveStock(),
            [
                WarehouseUserAssignmentTransfer::IS_ACTIVE => false,
            ],
        );

        $warehouseUserAssignmentCollectionRequestTransfer = (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($inactiveWarehouseUserAssignmentTransfer->setIsActive(true));

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->updateWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());

        $inactiveWarehouseUserAssignmentEntity = $this->tester->findWarehouseUserAssignment($activeWarehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
        $this->assertNotNull($inactiveWarehouseUserAssignmentEntity);
        $this->assertFalse($inactiveWarehouseUserAssignmentEntity->getIsActive());

        $activeWarehouseUserAssignmentEntity = $this->tester->findWarehouseUserAssignment($inactiveWarehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
        $this->assertNotNull($activeWarehouseUserAssignmentEntity);
        $this->assertTrue($activeWarehouseUserAssignmentEntity->getIsActive());
    }
}
