<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Communication\Controller;

use SprykerTest\Zed\Oms\PageObject\OrderDetailPage;
use SprykerTest\Zed\Oms\OmsCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Communication
 * @group Controller
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
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function _before(OmsCommunicationTester $i)
    {
        $i->haveTestStatemachine([static::OMS_ACTIVE_PROCESS]);
        $this->omsFacade = $i->getOmsFacade();
        $this->salesFacade = $i->getSalesFacade();

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
     * @return void
     */
    public function test(OmsCommunicationTester $i)
    {
        $i->amOnPage(
            sprintf(OrderDetailPage::URL_PATTERN, $this->orderTransfer->getIdSalesOrder())
        );
    }
}
