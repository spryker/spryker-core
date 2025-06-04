<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\Workflow;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\StrategyResolverInterface;
use Spryker\Zed\Checkout\CheckoutConfig;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface as ObsoleteCheckoutSaveOrderInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Throwable;

class CheckoutWorkflow implements CheckoutWorkflowInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface>>
     */
    protected $preConditionPluginStrategyResolver;

    /**
     * @var \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface>>
     */
    protected $saveOrderPluginStrategyResolver;

    /**
     * @var \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface>>
     */
    protected $postSavePluginStrategyResolver;

    /**
     * @var \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface>>
     */
    protected $preSavePluginStrategyResolver;

    /**
     * @var \Spryker\Zed\Checkout\CheckoutConfig
     */
    protected $checkoutConfig;

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface $omsFacade
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface>> $preConditionPluginStrategyResolver
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface>> $saveOrderPluginStrategyResolver
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface>> $postSavePluginStrategyResolver
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface>> $preSavePluginStrategyResolver
     * @param \Spryker\Zed\Checkout\CheckoutConfig $checkoutConfig
     */
    public function __construct(
        CheckoutToOmsFacadeInterface $omsFacade,
        StrategyResolverInterface $preConditionPluginStrategyResolver,
        StrategyResolverInterface $saveOrderPluginStrategyResolver,
        StrategyResolverInterface $postSavePluginStrategyResolver,
        StrategyResolverInterface $preSavePluginStrategyResolver,
        CheckoutConfig $checkoutConfig
    ) {
        $this->omsFacade = $omsFacade;
        $this->preConditionPluginStrategyResolver = $preConditionPluginStrategyResolver;
        $this->postSavePluginStrategyResolver = $postSavePluginStrategyResolver;
        $this->saveOrderPluginStrategyResolver = $saveOrderPluginStrategyResolver;
        $this->preSavePluginStrategyResolver = $preSavePluginStrategyResolver;
        $this->checkoutConfig = $checkoutConfig;
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

        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();
        $preConditionPlugins = $this->preConditionPluginStrategyResolver->get($quoteProcessFlowName);

        foreach ($preConditionPlugins as $preConditionPlugin) {
            $isPassed &= $preConditionPlugin->checkCondition($quoteTransfer, $checkoutResponse);
        }

        return (bool)$isPassed;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse Deprecated: SavedOrderTransfer should be used directly
     *
     * @throws \Throwable
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function doSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $maxAttempts = $this->checkoutConfig->getSaveOrderTransactionMaxAttempts();
        $attempt = 0;
        $success = false;

        while ($attempt < $maxAttempts && !$success) {
            try {
                $this->handleDatabaseTransaction(function () use ($quoteTransfer, $checkoutResponse) {
                    $this->doSaveOrderTransaction(
                        (new QuoteTransfer())->fromArray($quoteTransfer->modifiedToArray()),
                        $checkoutResponse,
                    );
                });
                $success = true;
            } catch (Throwable $e) {
                $attempt++;

                if ($attempt >= $maxAttempts) {
                    throw $e;
                }
            }
        }

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
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();
        $saveOrderPlugins = $this->saveOrderPluginStrategyResolver->get($quoteProcessFlowName);

        foreach ($saveOrderPlugins as $saveOrderPlugin) {
            if ($saveOrderPlugin instanceof ObsoleteCheckoutSaveOrderInterface) {
                $saveOrderPlugin->saveOrder($quoteTransfer, $checkoutResponse);

                continue;
            }

            $saveOrderPlugin->saveOrder($quoteTransfer, $checkoutResponse->getSaveOrder());
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
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();
        $postSavePlugins = $this->postSavePluginStrategyResolver->get($quoteProcessFlowName);

        foreach ($postSavePlugins as $postSavePlugin) {
            $postSavePlugin->executeHook($quoteTransfer, $checkoutResponse);
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
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();
        $preSavePlugins = $this->preSavePluginStrategyResolver->get($quoteProcessFlowName);

        foreach ($preSavePlugins as $preSavePlugin) {
            $quoteTransfer = $this->doPreSaveExecutePlugin($preSavePlugin, $quoteTransfer, $checkoutResponseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface|\Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveInterface|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface $preSavePlugin
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
