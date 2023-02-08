<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
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
 * @group DeleteWarehouseUserAssignmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class DeleteWarehouseUserAssignmentCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\WarehouseUser\WarehouseUserBusinessTester
     */
    protected WarehouseUserBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeleteWarehouseUserAssignmentCollectionDeletesWarehouseUserAssignmentFromPersistenceById(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $warehouseUserAssignmentCollectionDeleteCriteriaTransfer = (new WarehouseUserAssignmentCollectionDeleteCriteriaTransfer())
            ->addIdWarehouseUserAssignment($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->deleteWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());
        $this->tester->assertWarehouseUserAssignmentNotPersisted($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
    }

    /**
     * @return void
     */
    public function testDeleteWarehouseUserAssignmentCollectionDeletesWarehouseUserAssignmentFromPersistenceByUuid(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $stockTransfer = $this->tester->haveStock();
        $warehouseUserAssignmentTransfer = $this->tester->haveWarehouseUserAssignment(
            $userTransfer,
            $stockTransfer,
        );

        $warehouseUserAssignmentCollectionDeleteCriteriaTransfer = (new WarehouseUserAssignmentCollectionDeleteCriteriaTransfer())
            ->addUuid($warehouseUserAssignmentTransfer->getUuidOrFail());

        // Act
        $warehouseUserAssignmentCollectionResponseTransfer = $this->tester->getFacade()->deleteWarehouseUserAssignmentCollection($warehouseUserAssignmentCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments());
        $this->assertCount(0, $warehouseUserAssignmentCollectionResponseTransfer->getErrors());
        $this->tester->assertWarehouseUserAssignmentNotPersisted($warehouseUserAssignmentTransfer->getIdWarehouseUserAssignmentOrFail());
    }
}
