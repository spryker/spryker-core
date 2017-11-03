<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\Workflow;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface as ObsoleteCheckoutSaveOrderInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CheckoutWorkflow implements CheckoutWorkflowInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface[]
     */
    protected $preConditionStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface[]|\Spryker\Zed\Checkout\Dependency\Plugin\PlaceOrder\CheckoutSaveOrderInterface[]
     */
    protected $saveOrderStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface[]
     */
    protected $postSaveHookStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface[]
     */
    protected $preSaveStack;

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface[] $preConditionStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface[] $saveOrderStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface[] $postSaveHookStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreSaveHookInterface[] $preSave
     */
    public function __construct(
        array $preConditionStack,
        array $saveOrderStack,
        array $postSaveHookStack,
        array $preSave = []
    ) {
        $this->preConditionStack = $preConditionStack;
        $this->postSaveHookStack = $postSaveHookStack;
        $this->saveOrderStack = $saveOrderStack;
        $this->preSaveStack = $preSave;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $checkoutResponseTransfer = $this->resetCheckoutResponseTransfer($checkoutResponseTransfer);

        if (!$this->checkPreConditions($quoteTransfer, $checkoutResponseTransfer)) {
            return $checkoutResponseTransfer;
        }

        $quoteTransfer = $this->doPreSave($quoteTransfer);
        $quoteTransfer = $this->doSaveOrder($quoteTransfer, $checkoutResponseTransfer);

        $this->executePostHooks($quoteTransfer, $checkoutResponseTransfer);
        $this->updateCheckoutResponseSuccess($checkoutResponseTransfer);

        return $checkoutResponseTransfer;
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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    protected function hasErrors(CheckoutResponseTransfer $checkoutResponse)
    {
        return count($checkoutResponse->getErrors()) > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function updateCheckoutResponseSuccess(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $isSuccess = !$this->hasErrors($checkoutResponseTransfer);
        $checkoutResponseTransfer->setIsSuccess($isSuccess);
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
    protected function executePostHooks(QuoteTransfer $quoteTransfer, $checkoutResponse)
    {
        foreach ($this->postSaveHookStack as $postSaveHook) {
            $postSaveHook->executeHook($quoteTransfer, $checkoutResponse);
        }
    }

    /**
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function resetCheckoutResponseTransfer(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $checkoutResponseTransfer
            ->setSaveOrder(new SaveOrderTransfer())
            ->setIsSuccess(false);

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function doPreSave(QuoteTransfer $quoteTransfer)
    {
        foreach ($this->preSaveStack as $preSavePlugin) {
            $quoteTransfer = $preSavePlugin->preSave($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
