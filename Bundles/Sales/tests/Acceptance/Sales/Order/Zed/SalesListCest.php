<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Sales\Order\Zed;

use Acceptance\Sales\Order\Zed\PageObject\SalesDetailPage;
use Acceptance\Sales\Order\Zed\Tester\SalesListTester;

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
     * @param \Acceptance\Sales\Order\Zed\Tester\SalesListTester $i
     *
     * @return void
     */
    public function testOrderListShouldContainOrders(SalesListTester $i)
    {
        $i->createOrderWithOneItem();
        $i->seeListOfOrders();
    }

    /**
     * @param \Acceptance\Sales\Order\Zed\Tester\SalesListTester $i
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
