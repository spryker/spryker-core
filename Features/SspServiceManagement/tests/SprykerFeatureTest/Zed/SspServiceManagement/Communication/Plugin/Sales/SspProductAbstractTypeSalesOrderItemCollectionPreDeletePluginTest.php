<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Orm\Zed\SspServiceManagement\Persistence\SpySalesOrderItemProductAbstractTypeQuery;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Sales\SspProductAbstractTypeSalesOrderItemCollectionPreDeletePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SspProductAbstractTypeSalesOrderItemCollectionPreDeletePluginTest
 * Add your own group annotations below this line
 */
class SspProductAbstractTypeSalesOrderItemCollectionPreDeletePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STATE_MACHINE_PROCESS = 'Test01';

    /**
     * @var \SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Sales\SspProductAbstractTypeSalesOrderItemCollectionPreDeletePlugin
     */
    protected SspProductAbstractTypeSalesOrderItemCollectionPreDeletePlugin $plugin;

    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE_PROCESS]);

        $this->plugin = new SspProductAbstractTypeSalesOrderItemCollectionPreDeletePlugin();
    }

    /**
     * @return void
     */
    public function testPreDeleteRemovesProductAbstractTypesForGivenSalesOrderItems(): void
    {
        // Arrange
        $itemData = [
            ItemTransfer::SKU => 'sku-1',
            ItemTransfer::NAME => 'Test product',
            ItemTransfer::UNIT_PRICE => 10000,
            ItemTransfer::QUANTITY => 1,
        ];

        $saveOrderTransfer = $this->tester->haveOrder(
            [$itemData],
            static::TEST_STATE_MACHINE_PROCESS,
        );

        $itemTransfer = $saveOrderTransfer->getOrderItems()[0];
        $salesOrderItemId = $itemTransfer->getIdSalesOrderItem();

        $this->tester->createSalesOrderItemProductAbstractType([
            'id_sales_order_item' => $salesOrderItemId,
        ]);

        // Act
        $criteriaTransfer = new SalesOrderItemCollectionDeleteCriteriaTransfer();
        $criteriaTransfer->setSalesOrderItemIds([$salesOrderItemId]);
        $this->plugin->preDelete($criteriaTransfer);

        // Assert
        $afterCount = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($salesOrderItemId)
            ->count();

        $this->assertEquals(0, $afterCount, 'Product abstract type relation should be deleted');
    }

    /**
     * @return void
     */
    public function testPreDeleteDoNothingWithEmptySalesOrderItemIds(): void
    {
        // Arrange
        $criteriaTransfer = new SalesOrderItemCollectionDeleteCriteriaTransfer();
        $criteriaTransfer->setSalesOrderItemIds([]);

        // Act
        $this->plugin->preDelete($criteriaTransfer);
    }
}
