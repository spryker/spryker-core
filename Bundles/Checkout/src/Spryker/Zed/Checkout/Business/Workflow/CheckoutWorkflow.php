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
     * @var CheckoutPreConditionInterface[]
     */
    protected $preConditionStack;

    /**
     * @var CheckoutSaveOrderInterface[]
     */
    protected $saveOrderStack;

    /**
     * @var CheckoutPostSaveHookInterface[]
     */
    protected $postSaveHookStack;

    /**
     * @var CheckoutToOmsInterface
     */
    protected $omsFacade;

    /**
     * @param CheckoutPreConditionInterface[] $preConditionStack
     * @param CheckoutSaveOrderInterface[] $saveOrderStack
     * @param CheckoutPostSaveHookInterface[] $postSaveHookStack
     * @param CheckoutToOmsInterface $omsFacade
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
     * @param QuoteTransfer $quoteTransfer
     *
     * @return CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        $checkoutResponse = $this->createCheckoutResponseTransfer();
        $checkoutResponse->setIsSuccess(false);

        $this->checkPreConditions($quoteTransfer, $checkoutResponse);

        if (!$this->hasErrors($checkoutResponse)) {
            $quoteTransfer = $this->doSaveOrder($quoteTransfer, $checkoutResponse);
            if (!$this->hasErrors($checkoutResponse)) {
                $this->triggerStateMachine($checkoutResponse);
                $this->executePostHooks($quoteTransfer, $checkoutResponse);

                $isSuccess = !$this->hasErrors($checkoutResponse);
                $checkoutResponse->setIsSuccess($isSuccess);
            }
        }

        return $checkoutResponse;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
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
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    protected function hasErrors(CheckoutResponseTransfer $checkoutResponse)
    {
        return count($checkoutResponse->getErrors()) > 0;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return QuoteTransfer
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
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function triggerStateMachine(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $itemIds = [];

        foreach ($checkoutResponseTransfer->getSaveOrder()->getOrderItems() as $item) {
            $itemIds[] = $item->getIdSalesOrderItem();
        }

        $this->omsFacade->triggerEventForNewOrderItems($itemIds);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
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
     * @return CheckoutResponseTransfer
     */
    protected function createCheckoutResponseTransfer()
    {
        return new CheckoutResponseTransfer();
    }

}
