<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Business\SalesProductConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Business
 * @group SalesProductConnectorFacade
 * @group DeleteSalesOrderItemMetadataCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderItemMetadataCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesProductConnector\SalesProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testDeletesEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem1 = $saveOrderTransfer1->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $idSalesOrderItem2 = $saveOrderTransfer2->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->haveSalesOrderItemMetadata($idSalesOrderItem1);
        $idSalesOrderItemMetadata = $this->tester->haveSalesOrderItemMetadata($idSalesOrderItem2);
        $this->tester->haveSalesOrderItemMetadata($idSalesOrderItem1);

        $salesOrderItemMetadataCollectionDeleteCriteriaTransfer = (new SalesOrderItemMetadataCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItem1);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemMetadataCollection($salesOrderItemMetadataCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemMetadataTransfers = $this->tester->getSalesOrderItemMetadataEntities([$idSalesOrderItem1, $idSalesOrderItem2]);

        $this->assertCount(1, $salesOrderItemMetadataTransfers);
        $this->assertSame($idSalesOrderItemMetadata, $salesOrderItemMetadataTransfers[0]->getIdSalesOrderItemMetadata());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $idSalesOrderItemMetadata = $this->tester->haveSalesOrderItemMetadata($idSalesOrderItem);

        $salesOrderItemMetadataCollectionDeleteCriteriaTransfer = (new SalesOrderItemMetadataCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemMetadataCollection($salesOrderItemMetadataCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemMetadataTransfers = $this->tester->getSalesOrderItemMetadataEntities([$idSalesOrderItem]);

        $this->assertCount(1, $salesOrderItemMetadataTransfers);
        $this->assertSame($idSalesOrderItemMetadata, $salesOrderItemMetadataTransfers[0]->getIdSalesOrderItemMetadata());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $idSalesOrderItemMetadata = $this->tester->haveSalesOrderItemMetadata($idSalesOrderItem);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemMetadataCollection(new SalesOrderItemMetadataCollectionDeleteCriteriaTransfer());

        // Assert
        $salesOrderItemMetadataTransfers = $this->tester->getSalesOrderItemMetadataEntities([$idSalesOrderItem]);

        $this->assertCount(1, $salesOrderItemMetadataTransfers);
        $this->assertSame($idSalesOrderItemMetadata, $salesOrderItemMetadataTransfers[0]->getIdSalesOrderItemMetadata());
    }
}
