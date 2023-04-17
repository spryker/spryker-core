<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsWarehousesBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiWarehousesAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\WarehousesByPickingListsBackendResourceRelationshipPlugin;
use SprykerTest\Glue\PickingListsWarehousesBackendResourceRelationship\PickingListsWarehousesBackendResourceRelationshipTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PickingListsWarehousesBackendResourceRelationship
 * @group Plugin
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group WarehousesByPickingListsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class WarehousesByPickingListsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiConfig::RESOURCE_WAREHOUSES
     *
     * @var string
     */
    protected const RESOURCE_WAREHOUSES = 'warehouses';

    /**
     * @var \SprykerTest\Glue\PickingListsWarehousesBackendResourceRelationship\PickingListsWarehousesBackendResourceRelationshipTester
     */
    protected PickingListsWarehousesBackendResourceRelationshipTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddWarehousesRelationshipToGlueResourceTransfer(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingList();
        $glueResourceTransfers = [$this->tester->createPickingListResource($pickingListTransfer)];

        // Act
        (new WarehousesByPickingListsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfers[0]->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldSkipExpansionDueToWrongResourceType(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingList();
        $glueResourceTransfers = [$this->tester->createPickingListResource($pickingListTransfer)->setType('fake-type')];

        // Act
        (new WarehousesByPickingListsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfers[0]->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddCorrectWarehouseRelationshipId(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingList();
        $glueResourceTransfers = [$this->tester->createPickingListResource($pickingListTransfer)];

        // Act
        (new WarehousesByPickingListsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $this->assertSame($pickingListTransfer->getWarehouse()->getUuid(), $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddCorrectWarehouseRelationshipType(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingList();
        $glueResourceTransfers = [$this->tester->createPickingListResource($pickingListTransfer)];

        // Act
        (new WarehousesByPickingListsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $this->assertSame(static::RESOURCE_WAREHOUSES, $glueResourceTransfer->getType());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddCorrectWarehouseRelationshipAttributes(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->createPickingList();
        $glueResourceTransfers = [$this->tester->createPickingListResource($pickingListTransfer)];

        // Act
        (new WarehousesByPickingListsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $apiWarehousesAttributesTransfer = (new ApiWarehousesAttributesTransfer())
            ->fromArray($glueResourceTransfer->getAttributes()->toArray(), true);

        $this->assertSame($pickingListTransfer->getWarehouse()->getName(), $apiWarehousesAttributesTransfer->getName());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldHaveOnlyOneRelationship(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $firstPickingListTransfer = $this->tester->createPickingList($stockTransfer);
        $secondPickingListTransfer = $this->tester->createPickingList($stockTransfer);

        $glueResourceTransfers = [
            $this->tester->createPickingListResource($firstPickingListTransfer),
            $this->tester->createPickingListResource($secondPickingListTransfer),
        ];

        // Act
        (new WarehousesByPickingListsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(2, $glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfers[0]->getRelationships());
    }
}
