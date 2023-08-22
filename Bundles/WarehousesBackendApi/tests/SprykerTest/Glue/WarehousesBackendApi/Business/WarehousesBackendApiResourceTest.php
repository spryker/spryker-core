<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\WarehousesBackendApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StockConditionsTransfer;
use Generated\Shared\Transfer\StockCriteriaTransfer;
use Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer;
use SprykerTest\Glue\WarehousesBackendApi\WarehousesBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group WarehousesBackendApi
 * @group Business
 * @group WarehousesBackendApiResourceTest
 * Add your own group annotations below this line
 */
class WarehousesBackendApiResourceTest extends Unit
{
    /**
     * @uses \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiConfig::RESOURCE_WAREHOUSES
     *
     * @var string
     */
    protected const RESOURCE_WAREHOUSES = 'warehouses';

    /**
     * @var \SprykerTest\Glue\WarehousesBackendApi\WarehousesBackendApiTester
     */
    protected WarehousesBackendApiTester $tester;

    /**
     * @return void
     */
    public function testGetWarehouseResourceCollectionShouldReturnListOfWarehouseResources(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockCriteriaTransfer = (new StockCriteriaTransfer())
            ->setStockConditions((new StockConditionsTransfer())->addIdStock($stockTransfer->getIdStock()));

        // Act
        $warehouseResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getWarehouseResourceCollection($stockCriteriaTransfer);

        // Assert
        $this->assertCount(1, $warehouseResourceCollectionTransfer->getWarehouseResources());
    }

    /**
     * @return void
     */
    public function testGetWarehouseResourceCollectionShouldReturnCorrectResourceId(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockCriteriaTransfer = (new StockCriteriaTransfer())
            ->setStockConditions((new StockConditionsTransfer())->addIdStock($stockTransfer->getIdStock()));

        // Act
        $warehouseResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getWarehouseResourceCollection($stockCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $warehouseResource */
        $warehouseResource = $warehouseResourceCollectionTransfer->getWarehouseResources()->getIterator()->current();
        $this->assertSame($stockTransfer->getUuid(), $warehouseResource->getId());
    }

    /**
     * @return void
     */
    public function testGetWarehouseResourceCollectionShouldReturnCorrectResourceType(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockCriteriaTransfer = (new StockCriteriaTransfer())
            ->setStockConditions((new StockConditionsTransfer())->addIdStock($stockTransfer->getIdStock()));

        // Act
        $warehouseResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getWarehouseResourceCollection($stockCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $warehouseResource */
        $warehouseResource = $warehouseResourceCollectionTransfer->getWarehouseResources()->getIterator()->current();
        $this->assertSame(static::RESOURCE_WAREHOUSES, $warehouseResource->getType());
    }

    /**
     * @return void
     */
    public function testGetWarehouseResourceCollectionShouldReturnCorrectResourceAttributes(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $stockCriteriaTransfer = (new StockCriteriaTransfer())
            ->setStockConditions((new StockConditionsTransfer())->addIdStock($stockTransfer->getIdStock()));

        // Act
        $warehouseResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getWarehouseResourceCollection($stockCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $warehouseResource */
        $warehouseResource = $warehouseResourceCollectionTransfer->getWarehouseResources()->getIterator()->current();
        $warehousesBackendApiAttributesTransfer = (new WarehousesBackendApiAttributesTransfer())
            ->fromArray($warehouseResource->getAttributes()->toArray(), true);

        $this->assertSame($stockTransfer->getName(), $warehousesBackendApiAttributesTransfer->getName());
    }
}
