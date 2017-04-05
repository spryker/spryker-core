<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Presentation;

use SprykerTest\Zed\Sales\PageObject\SalesDetailPage;
use SprykerTest\Zed\Sales\PageObject\SalesListPage;
use SprykerTest\Zed\Sales\PresentationTester;

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
     * @param \SprykerTest\Zed\Sales\PresentationTester $i
     * @param \Sales\PageObject\SalesListPage $salesListPage
     *
     * @return void
     */
    public function testOrderListShouldContainOrders(PresentationTester $i, SalesListPage $salesListPage)
    {
        $i->createOrderWithOneItem();
        $salesListPage->seeListOfOrders();
    }

    /**
     * @param \SprykerTest\Zed\Sales\PresentationTester $i
     * @param \Sales\PageObject\SalesListPage $salesListPage
     *
     * @return void
     */
    public function testICanGoToLatestOrderDetailsPage(PresentationTester $i, SalesListPage $salesListPage)
    {
        $i->createOrderWithOneItem();

        $latestOrderId = $salesListPage->grabLatestOrderId();
        $url = SalesDetailPage::getOrderDetailsPageUrl($latestOrderId);

        $i->amOnPage($url);
        $i->canSeeCurrentUrlEquals($url);
    }

}
