<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms;

use Codeception\Actor;
use DateInterval;
use DateTime;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use ReflectionClass;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcher;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class OmsBusinessTester extends Actor
{
    use _generated\OmsBusinessTesterActions;

    /**
     * @return void
     */
    public function resetReservedStatesCache(): void
    {
        $reflectionResolver = new ReflectionClass(ActiveProcessFetcher::class);
        $reflectionProperty = $reflectionResolver->getProperty('reservedStatesCache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }

    /**
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderByStateMachineProcessName(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = $this->buildFakeQuote(
            $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE'])
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param string $storeName
     * @param string $eventName
     * @param string $stateName
     * @param int $orderItemsAmount
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createOrderWithExpiredEventTimeoutOrderItemsForStore(
        string $storeName,
        string $eventName,
        string $stateName,
        int $orderItemsAmount
    ): SpySalesOrder {
        $dateTime = new DateTime('now');
        $dateTime->sub(DateInterval::createFromDateString('1 day'));
        $processName = 'DummyPayment01';
        $salesOrderTransferDE = $this->haveOrder([], $processName);
        $salesOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($salesOrderTransferDE->getIdSalesOrder());
        $salesOrderEntity->setStore($storeName)->save();

        $salesOrderItemDefaults = [
            'state' => $stateName,
            'process' => $processName,
        ];
        for ($i = 0; $i < $orderItemsAmount; $i++) {
            $salesOrderItemEntity = $this->createSalesOrderItemForOrder($salesOrderTransferDE->getIdSalesOrder(), $salesOrderItemDefaults);
            $omsOrderItemStateEntity = $this->haveOmsOrderItemStateEntity($stateName);
            $omsEventTimeoutEntity = $this->haveOmsEventTimeoutEntity([
                'fk_sales_order_item' => $salesOrderItemEntity->getIdSalesOrderItem(),
                'fk_oms_order_item_state' => $omsOrderItemStateEntity->getIdOmsOrderItemState(),
                'event' => $eventName,
                'timeout' => $dateTime,
            ]);
            $salesOrderItemEntity->addEventTimeout($omsEventTimeoutEntity);
            $salesOrderItemEntity->setState($omsOrderItemStateEntity);
            $salesOrderEntity->addItem($salesOrderItemEntity);
        }

        return $salesOrderEntity;
    }

    /**
     * @param string $storeName
     * @param string $stateName
     * @param string $processName
     * @param int $orderItemsAmount One spy_sales_order_item is added always by the {@link \SprykerTest\Zed\Oms\_generated\OmsBusinessTesterActions::haveOrder()} method.
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createOrderWithOrderItemsInStateAndProcessForStore(
        string $storeName,
        string $stateName,
        string $processName,
        int $orderItemsAmount = 0
    ): SpySalesOrder {
        $salesOrderTransferDE = $this->haveOrder([], $processName);
        $salesOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($salesOrderTransferDE->getIdSalesOrder());
        $salesOrderEntity->setStore($storeName)->save();

        for ($i = 0; $i < $orderItemsAmount; $i++) {
            $salesOrderItemEntity = $this->createSalesOrderItemForOrder($salesOrderTransferDE->getIdSalesOrder(), ['state' => $stateName, 'process' => $processName]);
            $salesOrderEntity->addItem($salesOrderItemEntity);
            $this->haveOmsOrderItemStateEntity($stateName);
        }

        return $salesOrderEntity;
    }
}
