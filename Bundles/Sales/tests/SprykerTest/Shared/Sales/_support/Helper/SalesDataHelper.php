<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Sales\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Shared\Sales\Helper\Config\TesterSalesConfig;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SalesDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function haveOrder(array $override = [], $stateMachineProcessName = 'test')
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem($override)
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->makeEmpty()->build();

        $salesFacade = $this->getSalesFacade();
        $salesFacade = $this->configureSalesFacadeForTests($salesFacade, $stateMachineProcessName);
        $salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        return $checkoutResponseTransfer;
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
