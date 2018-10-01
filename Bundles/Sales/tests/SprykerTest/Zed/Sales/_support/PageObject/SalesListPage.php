<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\PageObject;

use SprykerTest\Zed\Sales\SalesPresentationTester;

class SalesListPage
{
    public const URL = '/sales';

    public const SELECTOR_ID_SALES_ORDER_ROWS = '//tbody/tr/td[1]';

    /**
     * @var \SprykerTest\Zed\Sales\SalesPresentationTester
     */
    protected $tester;

    /**
     * @param \SprykerTest\Zed\Sales\SalesPresentationTester $tester
     */
    public function __construct(SalesPresentationTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * @return string[]
     */
    public function grabOrderIdsFromGrid()
    {
        $this->tester->amOnPage(SalesListPage::URL);
        $this->tester->wait(2);

        return $this->tester->grabMultiple(SalesListPage::SELECTOR_ID_SALES_ORDER_ROWS);
    }

    /**
     * @return void
     */
    public function seeListOfOrders()
    {
        $this->tester->assertTrue(count($this->grabOrderIdsFromGrid()) > 0);
    }

    /**
     * @return int
     */
    public function grabLatestOrderId()
    {
        return $this->grabOrderIdsFromGrid()[0];
    }
}
