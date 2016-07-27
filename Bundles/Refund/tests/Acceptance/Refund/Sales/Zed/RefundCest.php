<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Refund\Sales\Zed;

use Acceptance\Refund\Sales\Zed\PageObject\SalesDetailPage;
use Acceptance\Refund\Sales\Zed\Tester\RefundTester;

/**
 * @group Acceptance
 * @group Refund
 * @group Sales
 * @group Zed
 * @group Refund
 */
class RefundCest
{

    /**
     * @param \Acceptance\Refund\Sales\Zed\Tester\RefundTester $i
     *
     * @return void
     */
    public function testRefundOneOfTwoItemsShouldNotIncludeExpenses(RefundTester $i)
    {
        $idSalesOrder = $i->createOrder();
        $idSalesOrderItemA = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);
        $idSalesOrderItemB = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);
        $i->triggerEventForNewOrderItems([$idSalesOrderItemA, $idSalesOrderItemB]);

        $salesDetailPageUrl = SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder);
        $i->amOnPage($salesDetailPageUrl);

        $i->moveItemUntilItIsRefunded($idSalesOrderItemA);
        $i->seeNumberOfRefunds(1);

        $itemTotalElementSelector = SalesDetailPage::getItemTotalElementSelector($idSalesOrderItemA);
        $expectedItemRefundAmount = (int)$i->grabAttributeFrom($itemTotalElementSelector, SalesDetailPage::ATTRIBUTE_ITEM_TOTAL_RAW);

        $i->assertSame($expectedItemRefundAmount, $i->getTotalRefundedAmount());
    }

    /**
     * @param \Acceptance\Refund\Sales\Zed\Tester\RefundTester $i
     *
     * @return void
     */
    public function testRefundTwoOfTwoItemsShouldIncludeExpenses(RefundTester $i)
    {
        $idSalesOrder = $i->createOrder();
        $idSalesOrderItemA = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);
        $idSalesOrderItemB = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);

        $i->triggerEventForNewOrderItems([$idSalesOrderItemA, $idSalesOrderItemB]);

        $salesDetailPageUrl = SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder);
        $i->amOnPage($salesDetailPageUrl);

        $i->moveItemUntilItIsRefunded($idSalesOrderItemA);
        $i->moveItemUntilItIsRefunded($idSalesOrderItemB);
        $i->seeNumberOfRefunds(2);

        $itemTotalElementSelector = SalesDetailPage::getItemTotalElementSelector($idSalesOrderItemA);
        $expectedItemRefundAmount = (int)$i->grabAttributeFrom($itemTotalElementSelector, SalesDetailPage::ATTRIBUTE_ITEM_TOTAL_RAW);

        $itemTotalElementSelector = SalesDetailPage::getItemTotalElementSelector($idSalesOrderItemA);
        $expectedItemRefundAmount += (int)$i->grabAttributeFrom($itemTotalElementSelector, SalesDetailPage::ATTRIBUTE_ITEM_TOTAL_RAW);

        $i->assertSame($expectedItemRefundAmount, $i->getTotalRefundedAmount());
    }



}
