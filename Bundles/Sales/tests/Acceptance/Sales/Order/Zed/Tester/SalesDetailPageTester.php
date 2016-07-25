<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Sales\Order\Zed\Tester;

use Acceptance\Sales\Order\Zed\PageObject\SalesDetailPage;

class SalesDetailPageTester extends SalesTester
{

    /**
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function openDetailPageForOrder($idSalesOrder)
    {
        $i = $this;
        $i->amOnPage(SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder));
    }

    /**
     * This method assumes that we are already on a details page
     *
     * @param int $rowPosition
     *
     * @return int
     */
    public function grabIdSalesOrderItemFromRow($rowPosition)
    {
        $i = $this;
        $idSalesOrderItem = $i->grabValueFrom(SalesDetailPage::getIdSalesOrderItemSelector($rowPosition));

        return $idSalesOrderItem;
    }

}
