<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Sales\Order\Zed\Tester;

use Acceptance\Sales\Order\Zed\PageObject\SalesListPage;
use Sales\AcceptanceTester;

class SalesTester extends AcceptanceTester
{

    /**
     * @return string[]
     */
    public function grabOrderIdsFromGrid()
    {
        $i = $this;
        $i->amOnPage(SalesListPage::URL);
        $i->wait(2);

        return $i->grabMultiple(SalesListPage::SELECTOR_ID_SALES_ORDER_ROWS);
    }

}
