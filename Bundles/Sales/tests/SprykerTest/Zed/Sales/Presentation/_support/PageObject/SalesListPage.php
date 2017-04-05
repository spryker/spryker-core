<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\PageObject;

use SprykerTest\Zed\Sales\PresentationTester;

class SalesListPage
{

    const URL = '/sales';

    const SELECTOR_ID_SALES_ORDER_ROWS = '//tbody/tr/td[1]';

    /**
     * @var \Sales\ZedPresentationTester
     */
    protected $tester;

    /**
     * @param \Sales\ZedPresentationTester $i
     */
    public function __construct(PresentationTester $i)
    {
        $this->tester = $i;
    }

    /**
     * @return string[]
     */
    public function grabOrderIdsFromGrid()
    {
        $i = $this->tester;
        $i->amOnPage(SalesListPage::URL);
        $i->wait(2);

        return $i->grabMultiple(SalesListPage::SELECTOR_ID_SALES_ORDER_ROWS);
    }

    /**
     * @return void
     */
    public function seeListOfOrders()
    {
        $i = $this->tester;
        $i->assertTrue(count($this->grabOrderIdsFromGrid()) > 0);
    }

    /**
     * @return int
     */
    public function grabLatestOrderId()
    {
        return $this->grabOrderIdsFromGrid()[0];
    }

}
