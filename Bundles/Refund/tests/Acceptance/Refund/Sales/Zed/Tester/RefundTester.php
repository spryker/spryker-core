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
    public function moveItemUntilItIsRefunded($idSalesOrderItem)
    {
        $i = $this;

        $triggerStatemachineButton = '//td/a[@data-id-item=' . $idSalesOrderItem . ']';
        $stateSelector = '//td[@data-qa-item-current-state=' . $idSalesOrderItem . ']';

        $i->click($triggerStatemachineButton);
        $i->click($triggerStatemachineButton);
        $i->click($triggerStatemachineButton);
        $i->click($triggerStatemachineButton);
        $i->click($triggerStatemachineButton);

        $i->assertSame('refunded', $i->grabTextFrom($stateSelector));
    }

    /**
     * @param int $expectedNumberOfRefundRows
     *
     * @return void
     */
    public function seeNumberOfRefunds($expectedNumberOfRefundRows)
    {
        $i = $this;
        $refundRowSelector = '//table[@data-qa="refund-list"]/tbody/tr[@data-qa="refund-row"]';
        $rows = $i->grabMultiple($refundRowSelector);

        $this->assertEquals($expectedNumberOfRefundRows, count($rows));
    }

    /**
     * @return int
     */
    public function getTotalRefundedAmount()
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
