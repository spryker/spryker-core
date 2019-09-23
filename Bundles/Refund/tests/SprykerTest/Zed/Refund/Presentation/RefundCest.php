<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\Presentation;

use SprykerTest\Zed\Refund\PageObject\SalesDetailPage;
use SprykerTest\Zed\Refund\RefundPresentationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Refund
 * @group Presentation
 * @group RefundCest
 * Add your own group annotations below this line
 */
class RefundCest
{
    /**
     * @skip
     *
     * @param \SprykerTest\Zed\Refund\RefundPresentationTester $i
     *
     * @return void
     */
    public function testRefundOneItemOfOrderRefundedAmountShouldBeSameAsItemGrandTotal(RefundPresentationTester $i)
    {
        $idSalesOrder = $i->createOrder();
        $idSalesOrderItemA = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);
        $idSalesOrderItemB = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);
        $i->triggerEventForNewOrderItems([$idSalesOrderItemA, $idSalesOrderItemB]);

        $salesDetailPageUrl = SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder);
        $i->amOnPage($salesDetailPageUrl);

        $i->refundItem($idSalesOrderItemA);
        $i->seeNumberOfRefunds(1);

        $itemTotalElementSelector = SalesDetailPage::getItemTotalElementSelector($idSalesOrderItemA);
        $expectedItemRefundAmount = (int)$i->grabAttributeFrom($itemTotalElementSelector, SalesDetailPage::ATTRIBUTE_ITEM_TOTAL_RAW);

        $i->assertSame($expectedItemRefundAmount, $i->grabTotalRefundedAmount());
    }

    /**
     * @skip
     *
     * @group single
     *
     * @param \SprykerTest\Zed\Refund\RefundPresentationTester $i
     *
     * @return void
     */
    public function testRefundOneItemOfOrderWithDiscountRefundedAmountShouldBeSameAsItemGrandTotal(RefundPresentationTester $i)
    {
        $idSalesOrder = $i->createOrder();
        $idSalesOrderItemA = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);
        $idSalesOrderItemB = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);

        $i->createDiscountForSalesOrderItem($idSalesOrderItemA);
        $i->triggerEventForNewOrderItems([$idSalesOrderItemA, $idSalesOrderItemB]);

        $salesDetailPageUrl = SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder);
        $i->amOnPage($salesDetailPageUrl);

        $i->refundItem($idSalesOrderItemA);
        $i->seeNumberOfRefunds(1);

        $itemTotalElementSelector = SalesDetailPage::getItemTotalElementSelector($idSalesOrderItemA);
        $expectedItemRefundAmount = (int)$i->grabAttributeFrom($itemTotalElementSelector, SalesDetailPage::ATTRIBUTE_ITEM_TOTAL_RAW);

        $i->assertSame($expectedItemRefundAmount, $i->grabTotalRefundedAmount());
    }

    /**
     * @skip
     *
     * @param \SprykerTest\Zed\Refund\RefundPresentationTester $i
     *
     * @return void
     */
    public function testWhenRefundAllItemsOfOrderRefundedAmountShouldBeSameAsGrandTotal(RefundPresentationTester $i)
    {
        $idSalesOrder = $i->createOrder();
        $idSalesOrderItemA = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);
        $idSalesOrderItemB = $i->createSalesOrderItemForOrder($idSalesOrder, ['process' => 'DummyPayment01']);

        $i->triggerEventForNewOrderItems([$idSalesOrderItemA, $idSalesOrderItemB]);

        $salesDetailPageUrl = SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder);
        $i->amOnPage($salesDetailPageUrl);

        $i->refundItem($idSalesOrderItemA);
        $i->refundItem($idSalesOrderItemB);
        $i->seeNumberOfRefunds(2);

        $grandTotal = (int)$i->grabAttributeFrom(SalesDetailPage::SELECTOR_GRAND_TOTAL, SalesDetailPage::ATTRIBUTE_GRAND_TOTAL_RAW);

        $i->assertSame($grandTotal, $i->grabTotalRefundedAmount());
    }
}
