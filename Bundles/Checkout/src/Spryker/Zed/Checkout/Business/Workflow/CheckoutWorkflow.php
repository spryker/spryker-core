<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\Workflow;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface as ObsoleteCheckoutSaveOrderInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CheckoutWorkflow implements CheckoutWorkflowInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface[]
     */
    protected $preConditionStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface[]|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface[]
     */
    protected $saveOrderStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface[]
     */
    protected $postSaveHookStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface[]|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface[]
     */
    protected $preSaveStack;

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface[] $preConditionStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface[]|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface[] $saveOrderStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface[] $postSaveHookStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface[]|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface[] $preSave
     */
    public function __construct(
        CheckoutToOmsFacadeInterface $omsFacade,
        array $preConditionStack,
        array $saveOrderStack,
        array $postSaveHookStack,
        array $preSave = []
    ) {
        $this->omsFacade = $omsFacade;
        $this->preConditionStack = $preConditionStack;
        $this->postSaveHookStack = $postSaveHookStack;
        $this->saveOrderStack = $saveOrderStack;
        $this->preSaveStack = $preSave;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();

        if (!$this->checkPreConditions($quoteTransfer, $checkoutResponseTransfer)) {
            return $checkoutResponseTransfer;
        }

        $quoteTransfer = $this->doPreSave($quoteTransfer, $checkoutResponseTransfer);
        $quoteTransfer = $this->doSaveOrder($quoteTransfer, $checkoutResponseTransfer);

        $this->runStateMachine($checkoutResponseTransfer->getSaveOrder());
        $this->doPostSave($quoteTransfer, $checkoutResponseTransfer);

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function isPlaceableOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = $this->createCheckoutResponseTransfer();

        $checkoutResponseTransfer->setIsSuccess($this->checkPreConditions($quoteTransfer, $checkoutResponseTransfer));

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function runStateMachine(SaveOrderTransfer $saveOrderTransfer)
    {
        $salesOrderItemIds = [];

        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        $this->omsFacade->triggerEventForNewOrderItems($salesOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    protected function checkPreConditions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $isPassed = true;

        foreach ($this->preConditionStack as $preCondition) {
            $isPassed &= $preCondition->checkCondition($quoteTransfer, $checkoutResponse);
        }

        return (bool)$isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse Deprecated: SavedOrderTransfer should be used directly
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function doSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $checkoutResponse) {
            $this->doSaveOrderTransaction($quoteTransfer, $checkoutResponse);
        });

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse Deprecated: SavedOrderTransfer should be used directly
     *
     * @return void
     */
    protected function doSaveOrderTransaction(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        foreach ($this->saveOrderStack as $orderSaver) {
            if ($orderSaver instanceof ObsoleteCheckoutSaveOrderInterface) {
                $orderSaver->saveOrder($quoteTransfer, $checkoutResponse);
                continue;
            }

            $orderSaver->saveOrder($quoteTransfer, $checkoutResponse->getSaveOrder());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function doPostSave(QuoteTransfer $quoteTransfer, $checkoutResponse)
    {
        foreach ($this->postSaveHookStack as $postSaveHook) {
            $postSaveHook->executeHook($quoteTransfer, $checkoutResponse);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponseTransfer()
    {
        return (new CheckoutResponseTransfer())
            ->setSaveOrder(new SaveOrderTransfer())
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function doPreSave(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        foreach ($this->preSaveStack as $preSavePlugin) {
            $quoteTransfer = $this->doPreSaveExecutePlugin($preSavePlugin, $quoteTransfer, $checkoutResponseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface $preSavePlugin
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer Deprecated: Will be removed with CheckoutPreSaveHookInterface (LTS)
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function doPreSaveExecutePlugin(
        $preSavePlugin,
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        if ($preSavePlugin instanceof CheckoutPreSaveHookInterface) {
            return $preSavePlugin->preSave($quoteTransfer, $checkoutResponseTransfer);
        }

        return $preSavePlugin->preSave($quoteTransfer);
    }
}
