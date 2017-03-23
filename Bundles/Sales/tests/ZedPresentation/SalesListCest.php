<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedPresentation;

use Sales\PageObject\SalesDetailPage;
use Sales\PageObject\SalesListPage;
use Sales\ZedPresentationTester;

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
     * @param \Sales\ZedPresentationTester $i
     * @param \Sales\PageObject\SalesListPage $salesListPage
     *
     * @return void
     */
    public function testOrderListShouldContainOrders(ZedPresentationTester $i, SalesListPage $salesListPage)
    {
        $i->createOrderWithOneItem();
        $salesListPage->seeListOfOrders();
    }

    /**
     * @param \Sales\ZedPresentationTester $i
     * @param \Sales\PageObject\SalesListPage $salesListPage
     *
     * @return void
     */
    public function testICanGoToLatestOrderDetailsPage(ZedPresentationTester $i, SalesListPage $salesListPage)
    {
        $i->createOrderWithOneItem();

        $latestOrderId = $salesListPage->grabLatestOrderId();
        $url = SalesDetailPage::getOrderDetailsPageUrl($latestOrderId);

        $i->amOnPage($url);
        $i->canSeeCurrentUrlEquals($url);
    }

}
