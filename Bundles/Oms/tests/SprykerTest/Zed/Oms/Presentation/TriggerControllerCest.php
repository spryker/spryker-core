<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Presentation;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Zed\Oms\OmsPresentationTester;
use SprykerTest\Zed\Oms\PageObject\OrderDetailPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Presentation
 * @group TriggerControllerCest
 * Add your own group annotations below this line
 */
class TriggerControllerCest
{
    protected const OMS_ACTIVE_PROCESS = 'Test01';
    protected const XPATH_CHANGE_STATUS_FORM = '.oms-trigger-form';

    /**
     * @param \SprykerTest\Zed\Oms\OmsPresentationTester $i
     *
     * @return void
     */
    public function testOrderStatusShouldBeChangedAfterFormSubmit(OmsPresentationTester $i): void
    {
        $salesFacade = $i->getSalesFacade();
        // Assign
        $i->amZed();
        $i->amLoggedInUser();
        $orderTransfer = $this->createOrder($i);
        $itemTransfer = $orderTransfer->getItems()[0];
        $initialState = $itemTransfer->getState()->getName();

        // Act
        $i->amOnPage(
            sprintf(OrderDetailPage::URL_PATTERN, $orderTransfer->getIdSalesOrder())
        );
        $i->submitForm(static::XPATH_CHANGE_STATUS_FORM, []);
        $i->waitForElement(static::XPATH_CHANGE_STATUS_FORM);

        $latestItemTransfer = $this->getItemLatestData($salesFacade, $orderTransfer);

        // Assert
        $i->see($latestItemTransfer->getState()->getName());

        $i->assertNotEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemLatestData(SalesFacadeInterface $salesFacade, OrderTransfer $orderTransfer): ItemTransfer
    {
        return $salesFacade->findOrderByIdSalesOrder(
            $orderTransfer->getIdSalesOrder()
        )->getItems()[0];
    }

    /**
     * @param \SprykerTest\Zed\Oms\OmsPresentationTester $i
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrder(OmsPresentationTester $i): OrderTransfer
    {
        $productTransfer = $i->haveProduct();

        return $i->haveOrderTransfer([
            'unitPrice' => 100,
            'sumPrice' => 100,
            'unitPriceToPayAggregation' => 100,
            'unitDiscountAmountFullAggregation' => 0,
            'amountSku' => $productTransfer->getSku(),
            'amount' => 5,
            'totals' => [
                'subtotal' => 500,
                'expense_total' => 0,
                'discount_total' => 0,
                'grand_total' => 500,
                'price_to_pay' => 500,
                'net_total' => 500,
            ],
        ], static::OMS_ACTIVE_PROCESS);
    }
}
