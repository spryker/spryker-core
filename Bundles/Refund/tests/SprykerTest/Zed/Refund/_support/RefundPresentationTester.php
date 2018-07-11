<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund;

use Codeception\Actor;
use Codeception\Scenario;
use SprykerTest\Zed\Refund\PageObject\RefundListPage;
use SprykerTest\Zed\Refund\PageObject\SalesDetailPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class RefundPresentationTester extends Actor
{
    use _generated\RefundPresentationTesterActions;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->amZed();
        $this->amLoggedInUser();
    }

    /**
     * @return void
     */
    public function canOpenRefundListPage()
    {
        $i = $this;
        $i->amOnPage(RefundListPage::URL);
        $i->seeElement(RefundListPage::SELECTOR_TABLE);
    }

    /**
     * @param int $idSalesOrderItem
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
