<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Generated\Shared\Transfer\AddressTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use Spryker\Zed\Propel\PropelFilterCriteria;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\SalesConfig;

class OrderManager
{
    const TEST_CUSTOMER_FIRST_NAME = 'test order';
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\OrderReferenceGeneratorInterface
     */
    protected $orderReferenceGenerator;

    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    private $salesConfiguration;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Business\Model\OrderReferenceGeneratorInterface $orderReferenceGenerator
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfiguration
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator,
        SalesConfig $salesConfiguration
    ) {
        $this->queryContainer = $queryContainer;
        $this->countryFacade = $countryFacade;
        $this->omsFacade = $omsFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
        $this->salesConfiguration = $salesConfiguration;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @return \Generated\Shared\Transfer\OrderTransfer
     * @throws \Exception
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->assertOrderRequirements($quoteTransfer);

        Propel::getConnection()->beginTransaction();

        $orderTransfer = $this->createOrderTransfer();

        try {
            $salesOrderEntity = $this->saveOrderEntity($quoteTransfer);
            $orderTransfer->fromArray($salesOrderEntity->toArray(), true);

            $this->saveOrderItems($quoteTransfer, $salesOrderEntity, $orderTransfer);

            $salesOrderEntity->setIsTest($this->isTestOrder($quoteTransfer));
            $orderTransfer->setIdSalesOrder($salesOrderEntity->getIdSalesOrder());
            $orderTransfer->setOrderReference($salesOrderEntity->getOrderReference());

            Propel::getConnection()->commit();

            $this->populateCheckoutResponseTransfer($checkoutResponseTransfer, $orderTransfer);

        } catch (\Exception $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function saveOrderEntity(QuoteTransfer $quoteTransfer)
    {
        $salesOrderEntity = $this->createSalesOrderEntity();
        $this->hydrateSalesOrderEntity($quoteTransfer, $salesOrderEntity);
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function saveOrderItems(
        QuoteTransfer $quoteTransfer,
        SpySalesOrder $salesOrderEntity,
        OrderTransfer $orderTransfer
    ) {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {

            $this->assertItemRequirements($itemTransfer);

            $processName = $this->salesConfiguration->determineProcessForOrderItem($quoteTransfer, $itemTransfer);
            $omsOrderProcessEntity = $this->omsFacade->getProcessEntity($processName);

            $salesOrderItemEntity = $this->createSalesOrderItemEntity();
            $this->hydrateSalesOrderItemEntity($salesOrderEntity, $omsOrderProcessEntity, $salesOrderItemEntity, $itemTransfer);

            $salesOrderItemEntity->setGrossPrice($itemTransfer->getUnitGrossPrice());
            $salesOrderItemEntity->save();

            $orderItemTransfer = clone $itemTransfer;
            $orderItemTransfer->setIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());

            $orderTransfer->addItem($orderItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function saveSalesOrderAddress(AddressTransfer $addressTransfer)
    {
        $salesOrderAddressEntity = $this->createSalesOrderAddressEntity();
        $this->hydrateSalesOrderAddress($addressTransfer, $salesOrderAddressEntity);
        $salesOrderAddressEntity->save();

        $addressTransfer->setIdSalesOrderAddress($salesOrderAddressEntity->getIdSalesOrderAddress());

        return $salesOrderAddressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        $orderListTransfer->setOrders(new \ArrayObject());

        $orderCollection = $this->getOrderCollection($orderListTransfer);

        foreach ($orderCollection as $order) {
            $orderTransfer = new OrderTransfer();
            $orderTransfer->fromArray($order->toArray(), true);

            $orderListTransfer->addOrder($orderTransfer);
        }

        return $orderListTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderById($idSalesOrder)
            ->findOne();

        if ($orderEntity === null) {
            return new OrderTransfer();
        }

        return $this->createOrderTransfer()->fromArray($orderEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getOrderCollection(OrderListTransfer $orderListTransfer)
    {
        $ordersQuery = $this->createOrderListQuery($orderListTransfer);

        $orderCollection = $ordersQuery->find();

        return $orderCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function createOrderListQuery(OrderListTransfer $orderListTransfer)
    {
        $filter = $orderListTransfer->getFilter();
        $criteria = new Criteria();

        if ($filter !== null) {
            $criteria = (new PropelFilterCriteria($filter))
                ->toCriteria();
        }

        $ordersQuery = $this
            ->queryContainer
            ->querySalesOrdersByCustomerId($orderListTransfer->getIdCustomer(), $criteria);

        return $ordersQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderEntity(QuoteTransfer $quoteTransfer, SpySalesOrder $orderEntity)
    {
        $orderEntity->setFkCustomer($quoteTransfer->getCustomer()->getIdCustomer());
        $orderEntity->fromArray($quoteTransfer->getCustomer()->toArray());
        $orderEntity->setBillingAddress($this->saveSalesOrderAddress($quoteTransfer->getBillingAddress()));
        $orderEntity->setShippingAddress($this->saveSalesOrderAddress($quoteTransfer->getShippingAddress()));
        $orderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($quoteTransfer));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $omsOrderProcessEntity
     * @param $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateSalesOrderItemEntity(
        SpySalesOrder $salesOrderEntity,
        SpyOmsOrderProcess $omsOrderProcessEntity,
        SpySalesOrderItem $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ) {

        $salesOrderItemEntity->fromArray($itemTransfer->toArray());
        $salesOrderItemEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItemEntity->setFkOmsOrderItemState(
            $this->omsFacade->getInitialStateEntity()->getIdOmsOrderItemState()
        );

        $salesOrderItemEntity->setProcess($omsOrderProcessEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function populateCheckoutResponseTransfer(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        OrderTransfer $orderTransfer
    ) {
        $saveOrderTransfer = $this->getSaveOrderTransfer($checkoutResponseTransfer);
        $this->hydrateSaveOrderTransfer($saveOrderTransfer, $orderTransfer);

        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateSaveOrderTransfer(SaveOrderTransfer $saveOrderTransfer, OrderTransfer $orderTransfer)
    {
        $saveOrderTransfer->fromArray($orderTransfer->toArray(), true);
        $orderItems = clone $orderTransfer->getItems();
        $saveOrderTransfer->setOrderItems($orderItems);
    }
    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function getSaveOrderTransfer(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $saveOrderTransfer = $checkoutResponseTransfer->getSaveOrder();
        if ($saveOrderTransfer === null) {
            $saveOrderTransfer = $this->createSaveOrderTransfer();
        }

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addresTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderAddress(AddressTransfer $addresTransfer, SpySalesOrderAddress $salesOrderAddressEntity)
    {
        $salesOrderAddressEntity->fromArray($addresTransfer->toArray());
        $salesOrderAddressEntity->setFkCountry(
            $this->countryFacade->getIdCountryByIso2Code($addresTransfer->getIso2Code())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isTestOrder(QuoteTransfer $quoteTransfer)
    {
        $shipingAddressTransfer = $quoteTransfer->getShippingAddress();
        if ($shipingAddressTransfer->getFirstName() === self::TEST_CUSTOMER_FIRST_NAME) {
            return true;
        }

        return false;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSalesOrderEntity()
    {
        return new SpySalesOrder();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemEntity()
    {
        return new SpySalesOrderItem();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        return new OrderTransfer();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function createSalesOrderAddressEntity()
    {
        return new SpySalesOrderAddress();
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createSaveOrderTransfer()
    {
        return new SaveOrderTransfer();
    }

    /**
     * @param $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireUnitGrossPrice()
            ->requireQuantity()
            ->requireName()
            ->requireSku();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertOrderRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireItems()
            ->requireTotals();
    }
}
