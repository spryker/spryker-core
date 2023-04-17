<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentsBackendApi\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiSalesShipmentsAttributesTransfer;
use Generated\Shared\Transfer\SalesShipmentConditionsTransfer;
use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerTest\Glue\ShipmentsBackendApi\ShipmentsBackendApiResourceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentsBackendApi
 * @group Resource
 * @group GetSalesShipmentsResourceCollectionTest
 * Add your own group annotations below this line
 */
class GetSalesShipmentsResourceCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiConfig::RESOURCE_SALES_SHIPMENTS
     *
     * @var string
     */
    protected const RESOURCE_SALES_SHIPMENTS = 'sales-shipments';

    /**
     * @var \SprykerTest\Glue\ShipmentsBackendApi\ShipmentsBackendApiResourceTester
     */
    protected ShipmentsBackendApiResourceTester $tester;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected SpySalesOrder $salesOrderEntity;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    protected SpySalesShipment $salesShipmentEntity;

    /**
     * @var \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected ObjectCollection $salesOrderItemEntities;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $this->salesShipmentEntity = $this->salesOrderEntity->getSpySalesShipments()->getFirst();
        $this->salesOrderItemEntities = $this->salesOrderEntity->getItems();
        $this->tester->updateSalesOrderItemsWithIdShipment($this->salesOrderItemEntities, $this->salesShipmentEntity->getIdSalesShipment());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionFilteredByIdSalesShipment(): void
    {
        // Arrange
        $salesShipmentConditionsTransfer = (new SalesShipmentConditionsTransfer())->addIdSalesShipment(
            $this->salesShipmentEntity->getIdSalesShipment(),
        )->setWithOrderItems(true);
        $salesShipmentCriteriaTransfer = (new SalesShipmentCriteriaTransfer())->setSalesShipmentConditions(
            $salesShipmentConditionsTransfer,
        );

        // Act
        $salesShipmentResourceCollectionTransfer = $this->tester->getResource()
            ->getSalesShipmentResourceCollection($salesShipmentCriteriaTransfer);

        // Arrange
        $this->assertSalesShipmentResourceCollection($salesShipmentResourceCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionFilteredBySalesOrderItemIds(): void
    {
        // Arrange
        $salesShipmentConditionsTransfer = (new SalesShipmentConditionsTransfer())->setSalesOrderItemIds(
            $this->salesOrderItemEntities->getColumnValues('idSalesOrderItem'),
        )->setWithOrderItems(true);

        $salesShipmentCriteriaTransfer = (new SalesShipmentCriteriaTransfer())->setSalesShipmentConditions(
            $salesShipmentConditionsTransfer,
        );

        // Act
        $salesShipmentResourceCollectionTransfer = $this->tester->getResource()
            ->getSalesShipmentResourceCollection($salesShipmentCriteriaTransfer);

        // Arrange
        $this->assertSalesShipmentResourceCollection($salesShipmentResourceCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionFilteredBySalesOrderItemUuids(): void
    {
        // Arrange
        $salesShipmentConditionsTransfer = (new SalesShipmentConditionsTransfer())->setOrderItemUuids(
            $this->salesOrderItemEntities->getColumnValues('uuid'),
        )->setWithOrderItems(true);

        $salesShipmentCriteriaTransfer = (new SalesShipmentCriteriaTransfer())->setSalesShipmentConditions(
            $salesShipmentConditionsTransfer,
        );

        // Act
        $salesShipmentResourceCollectionTransfer = $this->tester->getResource()
            ->getSalesShipmentResourceCollection($salesShipmentCriteriaTransfer);

        // Arrange
        $this->assertSalesShipmentResourceCollection($salesShipmentResourceCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer $salesShipmentsResourceCollectionTransfer
     *
     * @return void
     */
    protected function assertSalesShipmentResourceCollection(SalesShipmentResourceCollectionTransfer $salesShipmentsResourceCollectionTransfer): void
    {
        $this->assertCount(1, $salesShipmentsResourceCollectionTransfer->getSalesShipmentResources());
        $this->assertCount(1, $salesShipmentsResourceCollectionTransfer->getShipments());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $salesShipmentResourceTransfer */
        $salesShipmentResourceTransfer = $salesShipmentsResourceCollectionTransfer->getSalesShipmentResources()->getIterator()->current();
        $this->assertSame($this->salesShipmentEntity->getUuid(), $salesShipmentResourceTransfer->getId());
        $this->assertSame(static::RESOURCE_SALES_SHIPMENTS, $salesShipmentResourceTransfer->getType());
        $this->assertInstanceOf(ApiSalesShipmentsAttributesTransfer::class, $salesShipmentResourceTransfer->getAttributes());

        /** @var \Generated\Shared\Transfer\ApiSalesShipmentsAttributesTransfer $apiSalesShipmentsAttributesTransfer */
        $apiSalesShipmentsAttributesTransfer = $salesShipmentResourceTransfer->getAttributesOrFail();
        $this->assertSame($this->salesShipmentEntity->getRequestedDeliveryDate(), $apiSalesShipmentsAttributesTransfer->getRequestedDeliveryDate());
    }
}
