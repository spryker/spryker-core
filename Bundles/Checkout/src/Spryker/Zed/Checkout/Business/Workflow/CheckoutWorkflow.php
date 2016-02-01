<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Checkout\Business\Workflow;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Propel;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Shared\Library\Error\ErrorHandler;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreHydrationInterface;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;

class CheckoutWorkflow implements CheckoutWorkflowInterface
{

    /**
     * @var CheckoutPreConditionInterface[]
     */
    protected $preConditionStack;

    /**
     * @var CheckoutPreHydrationInterface[]
     */
    protected $preHydrationStack;

    /**
     * @var CheckoutOrderHydrationInterface[]
     */
    protected $orderHydrationStack;

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
     * @param CheckoutPreHydrationInterface[] $preHydrationStack
     * @param CheckoutOrderHydrationInterface[] $orderHydrationStack
     * @param CheckoutSaveOrderInterface[] $saveOrderStack
     * @param CheckoutPostSaveHookInterface[] $postSaveHookStack
     * @param \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface $omsFacade
     */
    public function __construct(
        array $preConditionStack,
        array $preHydrationStack,
        array $orderHydrationStack,
        array $saveOrderStack,
        array $postSaveHookStack,
        CheckoutToOmsInterface $omsFacade
    ) {
        $this->preConditionStack = $preConditionStack;
        $this->preHydrationStack = $preHydrationStack;
        $this->postSaveHookStack = $postSaveHookStack;
        $this->orderHydrationStack = $orderHydrationStack;
        $this->saveOrderStack = $saveOrderStack;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckout(CheckoutRequestTransfer $checkoutRequest)
    {
        $checkoutResponse = new CheckoutResponseTransfer();
        $checkoutResponse->setIsSuccess(false);

        $this->checkPreConditions($checkoutRequest, $checkoutResponse);

        if (!$this->hasErrors($checkoutResponse)) {
            $this->preHydrate($checkoutRequest, $checkoutResponse);

            $orderTransfer = $this->getOrderTransfer();
            $this->hydrateOrder($orderTransfer, $checkoutRequest);

            $orderTransfer = $this->doSaveOrder($orderTransfer, $checkoutResponse);

            $checkoutResponse->setOrder($orderTransfer);
            if (!$this->hasErrors($checkoutResponse)) {
                $this->triggerStateMachine($orderTransfer, $checkoutRequest);
                $this->executePostHooks($orderTransfer, $checkoutResponse);

                $isSuccess = !$this->hasErrors($checkoutResponse);
                $checkoutResponse->setIsSuccess($isSuccess);
            }
        }

        return $checkoutResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function preHydrate(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        foreach ($this->preHydrationStack as $preHydrator) {
            $preHydrator->execute($checkoutRequest, $checkoutResponse);
        }
    }

    /**
     * @TODO Refactor this code to make conditions throw specific exceptions, catch \Exception should not be used here
     *
     * @see https://github.com/spryker/spryker/issues/967
     *
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function checkPreConditions(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        try {
            foreach ($this->preConditionStack as $preCondition) {
                $preCondition->checkCondition($checkoutRequest, $checkoutResponse);
            }
        } catch (\Exception $e) {
            $error = $this->handleCheckoutError($e);

            $checkoutResponse
                ->addError($error)
                ->setIsSuccess(false);
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function doSaveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        Propel::getConnection()->beginTransaction();

        try {
            foreach ($this->saveOrderStack as $orderSaver) {
                $orderSaver->saveOrder($orderTransfer, $checkoutResponse);
            }

            if (!$this->hasErrors($checkoutResponse)) {
                Propel::getConnection()->commit();
            } else {
                Propel::getConnection()->rollBack();

                return $orderTransfer;
            }
        } catch (\Exception $e) {
            Propel::getConnection()->rollBack();

            $error = $this->handleCheckoutError($e);

            $checkoutResponse
                ->addError($error)
                ->setIsSuccess(false);
        }

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer()
    {
        return new OrderTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    protected function triggerStateMachine(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $itemIds = [];

        foreach ($orderTransfer->getItems() as $item) {
            $itemIds[] = $item->getIdSalesOrderItem();
        }

        $this->omsFacade->triggerEventForNewOrderItems($itemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    protected function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        foreach ($this->orderHydrationStack as $orderHydrator) {
            $orderHydrator->hydrateOrder($orderTransfer, $checkoutRequest);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function executePostHooks($orderTransfer, $checkoutResponse)
    {
        try {
            foreach ($this->postSaveHookStack as $postSaveHook) {
                $postSaveHook->executeHook($orderTransfer, $checkoutResponse);
            }
        } catch (\Exception $e) {
            $error = $this->handleCheckoutError($e);

            $checkoutResponse
                ->addError($error)
                ->setIsSuccess(false);
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function handleCheckoutError(\Exception $exception)
    {
        ErrorHandler::initialize()->handleException($exception, false, false);

        $error = new CheckoutErrorTransfer();

        $error
            ->setMessage($exception->getMessage())
            ->setErrorCode(CheckoutConstants::ERROR_CODE_UNKNOWN_ERROR)
            ->setType(get_class($exception))
            ->setTrace($exception->getTraceAsString());

        return $error;
    }

}
