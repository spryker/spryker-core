<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsSalesOrdersBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiOrdersAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\SalesOrdersByPickingListItemsBackendResourceRelationshipPlugin;
use SprykerTest\Glue\PickingListsSalesOrdersBackendResourceRelationship\PickingListsSalesOrdersBackendResourceRelationshipTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PickingListsSalesOrdersBackendResourceRelationship
 * @group Plugin
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group SalesOrdersByPickingListItemsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class SalesOrdersByPickingListItemsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig::RESOURCE_SALES_ORDERS
     *
     * @var string
     */
    protected const RESOURCE_SALES_ORDERS = 'sales-orders';

    /**
     * @var \SprykerTest\Glue\PickingListsSalesOrdersBackendResourceRelationship\PickingListsSalesOrdersBackendResourceRelationshipTester
     */
    protected PickingListsSalesOrdersBackendResourceRelationshipTester $tester;

    /**
     * @uses \Spryker\Shared\PickingList\PickingListConfig::STATUS_READY_FOR_PICKING
     *
     * @var string
     */
    protected const STATUS_READY_FOR_PICKING = 'ready-for-picking';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([PickingListsSalesOrdersBackendResourceRelationshipTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddSalesOrdersRelationshipToGlueResourceTransfer(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createSaveOrderTransferWithTwoItems();
        $pickingListTransfer = $this->tester->createPickingListTransfer([
            PickingListTransfer::USER => null,
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::STATUS => static::STATUS_READY_FOR_PICKING,
            PickingListTransfer::PICKING_LIST_ITEMS => [
                $this->tester->createPickingListItemTransfer($saveOrderTransfer->getOrderItems()->getIterator()->offsetGet(0)),
                $this->tester->createPickingListItemTransfer($saveOrderTransfer->getOrderItems()->getIterator()->offsetGet(1)),
            ],
        ]);

        $this->tester->createSaveOrderTransferWithTwoItems();

        $glueResourceTransfers = $this->tester->createGlueResourceTransfers($pickingListTransfer);

        // Act
        (new SalesOrdersByPickingListItemsBackendResourceRelationshipPlugin())->addRelationships(
            $glueResourceTransfers,
            new GlueRequestTransfer(),
        );

        // Assert
        $this->assertCount(2, $glueResourceTransfers);
        $this->assertGlueResource($glueResourceTransfers[0], $saveOrderTransfer->getOrderReferenceOrFail());
        $this->assertGlueResource($glueResourceTransfers[1], $saveOrderTransfer->getOrderReferenceOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     * @param string $expectedResourceId
     *
     * @return void
     */
    protected function assertGlueResource(GlueResourceTransfer $glueResourceTransfer, string $expectedResourceId): void
    {
        $this->assertCount(1, $glueResourceTransfer->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfer->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(
            static::RESOURCE_SALES_ORDERS,
            $glueResourceTransfer->getType(),
        );
        $this->assertInstanceOf(ApiOrdersAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertSame($expectedResourceId, $glueResourceTransfer->getId());
    }
}
