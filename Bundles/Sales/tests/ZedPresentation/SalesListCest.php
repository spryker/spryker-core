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
 * Auto-generated group annotations
 * @group Sales
 * @group ZedPresentation
 * @group SalesListCest
 * Add your own group annotations below this line
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
