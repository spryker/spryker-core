<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Refund\Sales\Zed\Tester;

use Acceptance\Refund\Sales\Zed\PageObject\SalesDetailPage;
use Refund\AcceptanceTester;

class RefundTester extends AcceptanceTester
{

    /**
     * @param $idSalesOrderItem
     *
     * @return void
     */
    public function refundItem($idSalesOrderItem)
    {
        $i = $this;

        $i->setItemState($idSalesOrderItem, SalesDetailPage::STATE_RETURNED);
        $i->reloadPage();
        $i->click(SalesDetailPage::BUTTON_REFUND);

        $currentStateSelector = SalesDetailPage::getCurrentStateSelector($idSalesOrderItem);
        $i->assertSame(SalesDetailPage::STATE_REFUNDED, $i->grabTextFrom($currentStateSelector));
    }

    /**
     * @param int $expectedNumberOfRefundRows
     *
     * @return void
     */
    public function seeNumberOfRefunds($expectedNumberOfRefundRows)
    {
        $i = $this;
        $rows = $i->grabMultiple(SalesDetailPage::SELECTOR_REFUND_ROW);

        $this->assertEquals($expectedNumberOfRefundRows, count($rows));
    }

    /**
     * @return int
     */
    public function grabTotalRefundedAmount()
    {
        $i = $this;
        $refundTotals = $i->grabMultiple(SalesDetailPage::REFUND_TOTAL_AMOUNT_SELECTOR, SalesDetailPage::ATTRIBUTE_ITEM_TOTAL_RAW);

        $refundTotal = 0;
        foreach ($refundTotals as $amount) {
            $refundTotal += (int)$amount;
        }

        return $refundTotal;
    }

}
