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
     * @var SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var OrderReferenceGeneratorInterface
     */
    protected $orderReferenceGenerator;

    /**
     * @var SalesConfig
     */
    private $salesConfiguration;

    /**
     * @param SalesQueryContainerInterface $queryContainer
     * @param SalesToCountryInterface $countryFacade
     * @param SalesToOmsInterface $omsFacade
     * @param OrderReferenceGeneratorInterface $orderReferenceGenerator
     * @param SalesConfig $salesConfiguration
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
     * @param QuoteTransfer $quoteTransfer
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     * @return OrderTransfer
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
     * @param QuoteTransfer $quoteTransfer
     *
     * @return SpySalesOrder
     */
    protected function saveOrderEntity(QuoteTransfer $quoteTransfer)
    {
        $salesOrderEntity = $this->createSalesOrderEntity();
        $this->hydrateSalesOrderEntity($quoteTransfer, $salesOrderEntity);
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param SpySalesOrder $salesOrderEntity
     * @param OrderTransfer $orderTransfer
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
     * @param AddressTransfer $addressTransfer
     *
     * @return SpySalesOrderAddress
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
     * @param OrderListTransfer $orderListTransfer
     *
     * @return OrderListTransfer
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
     * @return OrderTransfer
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
     * @param OrderListTransfer $orderListTransfer
     *
     * @return SpySalesOrder[]|ObjectCollection
     */
    protected function getOrderCollection(OrderListTransfer $orderListTransfer)
    {
        $ordersQuery = $this->createOrderListQuery($orderListTransfer);

        $orderCollection = $ordersQuery->find();

        return $orderCollection;
    }

    /**
     * @param OrderListTransfer $orderListTransfer
     *
     * @return SpySalesOrderQuery
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
     * @param QuoteTransfer $quoteTransfer
     * @param SpySalesOrder $orderEntity
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
     * @param SpySalesOrder $salesOrderEntity
     * @param SpyOmsOrderProcess $omsOrderProcessEntity
     * @param $salesOrderItemEntity
     * @param ItemTransfer $itemTransfer
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
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     * @param OrderTransfer $orderTransfer
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
     * @param SaveOrderTransfer $saveOrderTransfer
     * @param OrderTransfer $orderTransfer
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
     * @param CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return SaveOrderTransfer
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
     * @param AddressTransfer $addresTransfer
     * @param SpySalesOrderAddress $salesOrderAddressEntity
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
     * @param QuoteTransfer $quoteTransfer
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
     * @return SpySalesOrder
     */
    protected function createSalesOrderEntity()
    {
        return new SpySalesOrder();
    }

    /**
     * @return SpySalesOrderItem
     */
    protected function createSalesOrderItemEntity()
    {
        return new SpySalesOrderItem();
    }

    /**
     * @return OrderTransfer
     */
    protected function createOrderTransfer()
    {
        return new OrderTransfer();
    }

    /**
     * @return SpySalesOrderAddress
     */
    protected function createSalesOrderAddressEntity()
    {
        return new SpySalesOrderAddress();
    }

    /**
     * @return SaveOrderTransfer
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
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertOrderRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireItems()
            ->requireTotals();
    }
}
