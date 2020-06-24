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
     * @param int $orderItemsAmount
     *
     * @return void
     */
    public function createOrderWithExpiredEventTimeoutOrderItemsForStore(string $storeName, int $orderItemsAmount): void
    {
        $dateTime = new DateTime('now');
        $dateTime->sub(DateInterval::createFromDateString('1 day'));

        $stateName = 'timeout-store-test';
        $salesOrderTransferDE = $this->haveOrder([], 'DummyPayment01');
        $salesOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($salesOrderTransferDE->getIdSalesOrder());
        $salesOrderEntity->setStore($storeName)->save();

        for ($i = 0; $i < $orderItemsAmount; $i++) {
            $idSalesOrderItem = $this->createSalesOrderItemForOrder($salesOrderTransferDE->getIdSalesOrder());
            $omsOrderItemStateEntity = $this->haveOmsOrderItemStateEntity($stateName);
            $this->haveOmsEventTimeoutEntity([
                'fk_sales_order_item' => $idSalesOrderItem,
                'fk_oms_order_item_state' => $omsOrderItemStateEntity->getIdOmsOrderItemState(),
                'event' => 'foo',
                'timeout' => $dateTime,
            ]);
        }
    }
}
