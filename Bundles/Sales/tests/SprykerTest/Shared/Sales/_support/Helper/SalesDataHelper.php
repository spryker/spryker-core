<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Sales\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SaveOrderBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Shared\Sales\Helper\Config\TesterSalesConfig;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SalesDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     * @param string|null $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrder(array $override = [], $stateMachineProcessName = null)
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem($override)
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        return $this->persistOrder($quoteTransfer, $stateMachineProcessName);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrderFromQuote(QuoteTransfer $quoteTransfer, ?string $stateMachineProcessName = null): SaveOrderTransfer
    {
        return $this->persistOrder($quoteTransfer, $stateMachineProcessName);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function persistOrder(QuoteTransfer $quoteTransfer, string $stateMachineProcessName): SaveOrderTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
         */
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
    protected function configureSalesFacadeForTests(SalesFacadeInterface $salesFacade, $stateMachineProcessName)
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
    private function getSalesFacade()
    {
        return $this->getLocator()->sales()->facade();
    }
}
