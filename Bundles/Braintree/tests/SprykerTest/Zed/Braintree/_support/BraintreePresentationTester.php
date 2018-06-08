<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Braintree;

use Codeception\Actor;
use Codeception\Scenario;
use DateInterval;
use SprykerTest\Zed\Sales\PageObject\SalesDetailPage;
use SprykerTest\Zed\Sales\PageObject\SalesListPage;

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
class BraintreePresentationTester extends Actor
{
    use _generated\BraintreePresentationTesterActions;

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
     * This will move a braintree credit card order through all states until the order is closed
     *
     * @return void
     */
    public function closeCreditCardOrderHappyCase()
    {
        $i = $this;
        $i->goToSalesDetailPage();
        $i->waitForText('BraintreeCreditCard01', 2);
        $i->click('start shipment process');
        $i->waitForText('capture succeeded', 2);
        $i->letItemSleepFor('15 days');
        $i->waitForText('closed', 2);
    }

    /**
     * This will move a braintree PayPal order through all states until the order is closed
     *
     * @return void
     */
    public function closePayPalOrderHappyCase()
    {
        $i = $this;
        $i->goToSalesDetailPage();
        $i->waitForText('BraintreePayPal01', 2);
        $i->click('start shipment process');
        $i->waitForText('capture succeeded', 2);
        $i->letItemSleepFor('15 days');
        $i->waitForText('closed', 2);
    }

    /**
     * This will refund a item of a braintree credit card order and close it
     *
     * @return void
     */
    public function refundItemAndCloseOrder()
    {
        $i = $this;
        $i->goToSalesDetailPage();
        $i->waitForText('BraintreeCreditCard01', 2);
        $i->click('start shipment process');
        $i->waitForText('capture succeeded', 2);
        $i->click('start refund process');
        $i->waitForText('closed', 2);
    }

    /**
     * @return void
     */
    protected function goToSalesDetailPage()
    {
        $i = $this;
        $i->amOnPage(SalesListPage::URL);
        $i->seeCurrentUrlEquals(SalesListPage::URL);
        $i->wait(5);
        $salesOrderIds = $i->grabMultiple(SalesListPage::SELECTOR_ID_SALES_ORDER_ROWS);
        $latestOrderId = $salesOrderIds[0];
        $url = SalesDetailPage::getOrderDetailsPageUrl($latestOrderId);
        $i->amOnPage($url);
    }

    /**
     * @param string $timeout
     *
     * @return void
     */
    protected function letItemSleepFor($timeout)
    {
        $rowPosition = 1;
        $idSalesOrderItem = $this->grabValueFrom(SalesDetailPage::getIdSalesOrderItemSelector($rowPosition));
        $this->moveItemAfterTimeOut($idSalesOrderItem, DateInterval::createFromDateString($timeout));
        $this->checkTimeout();
        $this->reloadPage();
    }
}
