<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedPresentation\Sales\Order\Zed\Tester;

class SalesListTester extends SalesTester
{

    /**
     * @return void
     */
    public function seeListOfOrders()
    {
        $i = $this;
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
