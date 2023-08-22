<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsShipmentsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\SalesShipmentsBackendApiAttributesTransfer;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\SalesShipmentsByPickingListsBackendResourceRelationshipPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Glue\PickingListsShipmentsBackendResourceRelationship\PickingListsShipmentsBackendResourceRelationshipTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PickingListsShipmentsBackendResourceRelationship
 * @group Plugin
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group SalesShipmentsByPickingListsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class SalesShipmentsByPickingListsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiConfig::RESOURCE_SALES_SHIPMENTS
     *
     * @var string
     */
    protected const RESOURCE_SALES_SHIPMENTS = 'sales-shipments';

    /**
     * @var \SprykerTest\Glue\PickingListsShipmentsBackendResourceRelationship\PickingListsShipmentsBackendResourceRelationshipTester
     */
    protected PickingListsShipmentsBackendResourceRelationshipTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddSalesShipmentsRelationshipToGlueResourceTransfer(): void
    {
        // Arrange
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        /** @var \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer */
        [$pickingListTransfer, $shipmentTransfer] = $this->tester->createPickingListWithItemAndShipment();

        $pickingListItemsBackendApiAttributesTransfer = $this->tester->mapPickingListItemsToPickingListItemsBackendApiAttributesTransfers($pickingListTransfer->getPickingListItems());
        $glueResourceTransfers = $this->tester->addPickingListItemsRelationshipResourceToGlueResourceTransfers($pickingListItemsBackendApiAttributesTransfer);

        // Act
        (new SalesShipmentsByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfers[0]->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(static::RESOURCE_SALES_SHIPMENTS, $glueResourceTransfer->getType());
        $this->assertInstanceOf(SalesShipmentsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertSame($shipmentTransfer->getUuid(), $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddSalesShipmentsRelationshipToGlueResourceWithWrongType(): void
    {
        // Arrange
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        [$pickingListTransfer] = $this->tester->createPickingListWithItemAndShipment();

        $pickingListItemsBackendApiAttributesTransfer = $this->tester->mapPickingListItemsToPickingListItemsBackendApiAttributesTransfers($pickingListTransfer->getPickingListItems());
        $glueResourceTransfers = $this->tester->addPickingListItemsRelationshipResourceToGlueResourceTransfers($pickingListItemsBackendApiAttributesTransfer);
        $glueResourceTransfers[0]->setType('fake-type');

        // Act
        (new SalesShipmentsByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfers[0]->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldThrowExceptionDueToEmptyOrderItemField(): void
    {
        // Arrange
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        [$pickingListTransfer] = $this->tester->createPickingListWithItemAndShipment();

        $pickingListItemsBackendApiAttributesTransfer = $this->tester->mapPickingListItemsToPickingListItemsBackendApiAttributesTransfers($pickingListTransfer->getPickingListItems());
        $pickingListItemsBackendApiAttributesTransfer[0]->setOrderItem(null);
        $glueResourceTransfers = $this->tester->addPickingListItemsRelationshipResourceToGlueResourceTransfers($pickingListItemsBackendApiAttributesTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new SalesShipmentsByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, new GlueRequestTransfer());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldThrowExceptionDueToEmptyOrderItemUuidField(): void
    {
        // Arrange
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        [$pickingListTransfer] = $this->tester->createPickingListWithItemAndShipment();

        $pickingListItemsBackendApiAttributesTransfer = $this->tester->mapPickingListItemsToPickingListItemsBackendApiAttributesTransfers($pickingListTransfer->getPickingListItems());
        $pickingListItemsBackendApiAttributesTransfer[0]->getOrderItemOrFail()->setUuid(null);
        $glueResourceTransfers = $this->tester->addPickingListItemsRelationshipResourceToGlueResourceTransfers($pickingListItemsBackendApiAttributesTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new SalesShipmentsByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, new GlueRequestTransfer());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddSalesShipmentsRelationshipToGlueResourceWithWrongOrderItemUuid(): void
    {
        // Arrange
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        [$pickingListTransfer] = $this->tester->createPickingListWithItemAndShipment();

        $pickingListItemsBackendApiAttributesTransfer = $this->tester->mapPickingListItemsToPickingListItemsBackendApiAttributesTransfers($pickingListTransfer->getPickingListItems());
        $pickingListItemsBackendApiAttributesTransfer[0]->getOrderItemOrFail()->setUuid('fake-uuid');
        $glueResourceTransfers = $this->tester->addPickingListItemsRelationshipResourceToGlueResourceTransfers($pickingListItemsBackendApiAttributesTransfer);

        // Act
        (new SalesShipmentsByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfers[0]->getRelationships());
    }
}
