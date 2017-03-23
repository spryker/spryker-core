<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedPresentation\Sales\Order\Zed;

use ZedPresentation\Sales\Order\Zed\PageObject\SalesDetailPage;
use ZedPresentation\Sales\Order\Zed\Tester\SalesListTester;

/**
 * @group Acceptance
 * @group Sales
 * @group Order
 * @group Zed
 * @group SalesListCest
 */
class SalesListCest
{

    /**
     * @param \ZedPresentation\Sales\Order\Zed\Tester\SalesListTester $i
     *
     * @return void
     */
    public function testOrderListShouldContainOrders(SalesListTester $i)
    {
        $i->createOrderWithOneItem();
        $i->seeListOfOrders();
    }

    /**
     * @param \ZedPresentation\Sales\Order\Zed\Tester\SalesListTester $i
     *
     * @return void
     */
    public function testICanGoToLatestOrderDetailsPage(SalesListTester $i)
    {
        $i->createOrderWithOneItem();

        $latestOrderId = $i->grabLatestOrderId();
        $url = SalesDetailPage::getOrderDetailsPageUrl($latestOrderId);

        $i->amOnPage($url);
        $i->canSeeCurrentUrlEquals($url);
    }

}
