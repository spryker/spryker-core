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
use SprykerTest\Zed\Oms\Helper\OmsHelper;

class SalesDataHelper extends Module
{
    use LocatorHelperTrait;

    public const NAMESPACE_ROOT = '\\';

    /**
     * @var \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface[]
     */
    protected $saveOrderStack = [];

    /**
     * @param array $override
     * @param string|null $stateMachineProcessName
     * @param \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface[] $saveOrderStack
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrder(
        array $override = [],
        $stateMachineProcessName = null,
        array $saveOrderStack = []
    ) {
        $this->saveOrderStack = $saveOrderStack;
        $quoteTransfer = $this->createQuoteTransfer($override);

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
        $saveOrderTransfer = $this->createOrder($quoteTransfer, $stateMachineProcessName);
        $this->executeSaveOrderPlugins($quoteTransfer, $saveOrderTransfer);

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $stateMachineProcessName
     * @param \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface[] $saveOrderStack
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveOrderUsingPreparedQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        ?string $stateMachineProcessName = null,
        array $saveOrderStack = []
    ): SaveOrderTransfer {

        $this->getOmsHelperModule()->configureTestStateMachine([$stateMachineProcessName]);

        $this->saveOrderStack = $saveOrderStack;

        $saveOrderTransfer = $this->createOrder($quoteTransfer, $stateMachineProcessName);
        $this->executeSaveOrderPlugins($quoteTransfer, $saveOrderTransfer);

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

        /** @var \Spryker\Zed\Kernel\Business\AbstractFacade $salesFacade */
        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createOrder(QuoteTransfer $quoteTransfer, ?string $stateMachineProcessName = null): SaveOrderTransfer
    {
        $saveOrderTransfer = (new SaveOrderBuilder())->makeEmpty()->build();

        $salesFacade = $this->getSalesFacade();
        if ($stateMachineProcessName) {
            $salesFacade = $this->configureSalesFacadeForTests($salesFacade, $stateMachineProcessName);
        }

        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        return $saveOrderTransfer;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade()
    {
        return $this->getLocator()->sales()->facade();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(array $override = []): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withItem($override)
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function executeSaveOrderPlugins(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        foreach ($this->saveOrderStack as $orderSaver) {
            $orderSaver->saveOrder($quoteTransfer, $saveOrderTransfer);
        }
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Zed\Oms\Helper\OmsHelper
     */
    protected function getOmsHelperModule(): OmsHelper
    {
        if ($this->hasModule(static::NAMESPACE_ROOT . OmsHelper::class)) {
            return $this->getModule(static::NAMESPACE_ROOT . OmsHelper::class);
        }

        $this->moduleContainer->create(static::NAMESPACE_ROOT . OmsHelper::class);

        return $this->getModule(static::NAMESPACE_ROOT . OmsHelper::class);
    }
}
