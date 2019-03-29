<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Shipment\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SaveOrderBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Shared\Shipment\Helper\Config\TesterSalesConfig;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShipmentOrderDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     * @param string|null $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveOrderUsingPreparedQuoteTransfer(QuoteTransfer $quoteTransfer = null, string $stateMachineProcessName = null): SaveOrderTransfer
    {
        if ($quoteTransfer === null) {
            $quoteTransfer = $this->createQuoteTransfer();
        }

        $saveOrderTransfer = (new SaveOrderBuilder())->makeEmpty()->build();

        $salesFacade = $this->getSalesFacade();
        if ($stateMachineProcessName) {
            $salesFacade = $this->configureSalesFacadeForTests($salesFacade, $stateMachineProcessName);
        }

        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        return $saveOrderTransfer;
    }

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param string $stateMachineProcessName
     *
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function configureSalesFacadeForTests(SalesFacadeInterface $salesFacade, $stateMachineProcessName): SalesFacadeInterface
    {
        $salesBusinessFactory = new SalesBusinessFactory();

        $salesConfig = new TesterSalesConfig();
        $salesConfig->setStateMachineProcessName($stateMachineProcessName);
        $salesBusinessFactory->setConfig($salesConfig);

        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getLocator()->sales()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withItem()
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
    }
}
