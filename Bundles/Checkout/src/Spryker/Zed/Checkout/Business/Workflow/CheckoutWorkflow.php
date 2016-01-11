<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Checkout\Business\Workflow;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;

class CheckoutWorkflow implements CheckoutWorkflowInterface
{

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface[]
     */
    protected $preConditionStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface[]
     */
    protected $saveOrderStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface[]
     */
    protected $postSaveHookStack;

    /**
     * @var \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface[] $preConditionStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface[] $saveOrderStack
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface[] $postSaveHookStack
     * @param \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface $omsFacade
     */
    public function __construct(
        array $preConditionStack,
        array $saveOrderStack,
        array $postSaveHookStack,
        CheckoutToOmsInterface $omsFacade
    ) {
        $this->preConditionStack = $preConditionStack;
        $this->postSaveHookStack = $postSaveHookStack;
        $this->saveOrderStack = $saveOrderStack;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @todo rename to placeOrder
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        $checkoutResponse = new CheckoutResponseTransfer();
        $checkoutResponse->setIsSuccess(false);

        $this->checkPreConditions($quoteTransfer, $checkoutResponse);

        if (!$this->hasErrors($checkoutResponse)) {
            $quoteTransfer = $this->doSaveOrder($quoteTransfer, $checkoutResponse);
            if (!$this->hasErrors($checkoutResponse)) {
                $this->triggerStateMachine($quoteTransfer);
                $this->executePostHooks($quoteTransfer, $checkoutResponse);

                $isSuccess = !$this->hasErrors($checkoutResponse);
                $checkoutResponse->setIsSuccess($isSuccess);
            }
        }

        return $checkoutResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function checkPreConditions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        foreach ($this->preConditionStack as $preCondition) {
            $preCondition->checkCondition($quoteTransfer, $checkoutResponse);
        }
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     * @return \Generated\Shared\Transfer\QuoteTransfer
     *
     * @throws \Exception
     */
    protected function doSaveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        Propel::getConnection()->beginTransaction();

        try {
            foreach ($this->saveOrderStack as $orderSaver) {
                $orderSaver->saveOrder($quoteTransfer, $checkoutResponse);
            }

            if (!$this->hasErrors($checkoutResponse)) {
                Propel::getConnection()->commit();
            } else {
                Propel::getConnection()->rollBack();

                return $quoteTransfer;
            }
        } catch (\Exception $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function triggerStateMachine(QuoteTransfer $quoteTransfer)
    {
        $itemIds = [];

        foreach ($quoteTransfer->getItems() as $item) {
            $itemIds[] = $item->getIdSalesOrderItem();
        }

        $this->omsFacade->triggerEventForNewOrderItems($itemIds);
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

}
