<?php

namespace SprykerTest\Zed\Oms\Presentation;


use Generated\Shared\Transfer\ItemTransfer;
use SprykerTest\Zed\Oms\OmsPresentationTester;
use SprykerTest\Zed\Oms\PageObject\OrderDetailPage;
use SprykerTest\Zed\Oms\OmsCommunicationTester;

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

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @param \SprykerTest\Zed\Oms\OmsPresentationTester $i
     *
     * @return void
     */
    public function _before(OmsPresentationTester $i)
    {
        $this->omsFacade = $i->getOmsFacade();
        $this->salesFacade = $i->getSalesFacade();
        $i->configureTestStateMachine([static::OMS_ACTIVE_PROCESS]);

        $productTransfer = $i->haveProduct();


        $this->orderTransfer = $i->haveOrderTransfer([
            'unitPrice' => 100,
            'sumPrice' => 100,
            'amountSku' => $productTransfer->getSku(),
            'amount' => 5,
            'totals' => [
                'subtotal' => 500,
                'expense_total' => 0,
                'discount_total' => 0,
                'grand_total' => 500,
                'price_to_pay' => 500,
                'net_total' => 500,
            ]
        ], static::OMS_ACTIVE_PROCESS);
    }

    /**
     * @group her
     * @return void
     */
    public function testFirst(OmsPresentationTester $i)
    {
        $i->amZed();
        $i->amLoggedInUser();
        $itemTransfer = $this->orderTransfer->getItems()[0];
        $initialState = $itemTransfer->getState()->getName();

        // Act
        $i->amOnPage(
            sprintf(OrderDetailPage::URL_PATTERN, $this->orderTransfer->getIdSalesOrder())
        );

        $i->makeScreenshot();

//        $i->submitForm('//*[@id="items"]/div[2]/div/div/div[2]/table/tbody[1]/tr/td[8]/form', []);
//
//        $latestItemTransfer = $this->getItemLatestData();
//
//        $i->assertNotEquals($initialState, $latestItemTransfer->getState()->getName());
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemLatestData(): ItemTransfer
    {
        return $this->salesFacade->findOrderByIdSalesOrder(
            $this->orderTransfer->getIdSalesOrder()
        )->getItems()[0];
    }
}
