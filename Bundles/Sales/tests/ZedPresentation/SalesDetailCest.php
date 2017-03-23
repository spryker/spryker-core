<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedPresentation\Sales\Order\Zed;

use Sales\PageObject\SalesDetailPage;
use ZedPresentation\Sales\Order\Zed\Tester\SalesDetailPageTester;
use ZedPresentation\Sales\Order\Zed\Tester\SalesListTester;

/**
 * @group Acceptance
 * @group Sales
 * @group Order
 * @group Zed
 * @group SalesDetailCest
 */
class SalesDetailCest
{

    /**
     * @param \ZedPresentation\Sales\Order\Zed\Tester\SalesDetailPageTester $i
     * @param \ZedPresentation\Sales\Order\Zed\Tester\SalesListTester $salesListTester
     *
     * @return void
     */
    public function testThatOrderDetailPageIsVisibleWhenOrderExists(SalesDetailPageTester $i, SalesListTester $salesListTester)
    {
        $i->createOrderWithOneItem();

        $idSalesOrder = $salesListTester->grabLatestOrderId();
        $i->amOnPage(SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder));
        $i->waitForElement('#items', 3);
        $i->seeElement(['xpath' => SalesDetailPage::getSalesOrderItemRowSelector(1)]);
    }

}
