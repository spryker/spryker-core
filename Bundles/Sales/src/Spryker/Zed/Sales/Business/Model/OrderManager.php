<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Generated\Shared\Transfer\AddressTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Spryker\Zed\Propel\PropelFilterCriteria;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderManager
{

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

    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator
    ) {
        $this->queryContainer = $queryContainer;
        $this->countryFacade = $countryFacade;
        $this->omsFacade = $omsFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function saveOrder(OrderTransfer $orderTransfer)
    {
        Propel::getConnection()->beginTransaction();

        try {
            $orderEntity = $this->saveOrderEntity($orderTransfer);

            // @todo: Should detect process per item, not per order
            $processName = $this->omsFacade->selectProcess($orderTransfer);
            $orderProcess = $this->omsFacade->getProcessEntity($processName);

            $this->saveOrderItems($orderTransfer, $orderEntity, $orderProcess);

            $orderTransfer->setIdSalesOrder($orderEntity->getIdSalesOrder());
            $orderTransfer->setOrderReference($orderEntity->getOrderReference());

            Propel::getConnection()->commit();
        } catch (\Exception $e) {
            Propel::getConnection()->rollBack();
            throw $e;
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function saveOrderEntity(OrderTransfer $orderTransfer)
    {
        $orderEntity = new SpySalesOrder();

        // catch-all save; this includes an optional fk_customer
        $orderEntity->fromArray($orderTransfer->toArray());

        $orderEntity->setBillingAddress($this->saveAddressTransfer($orderTransfer->getBillingAddress()));
        $orderEntity->setShippingAddress($this->saveAddressTransfer($orderTransfer->getShippingAddress()));

        $orderEntity->setGrandTotal($orderTransfer->getTotals()
            ->getGrandTotalWithDiscounts());
        $orderEntity->setSubtotal($orderTransfer->getTotals()
            ->getSubtotal());

        $orderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($orderTransfer));

        $orderEntity->save();

        return $orderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param $orderEntity
     * @param $orderProcess
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function saveOrderItems(OrderTransfer $orderTransfer, $orderEntity, $orderProcess)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $quantity = $item->getQuantity() !== null ? $item->getQuantity() : 1;

            $itemEntity = new SpySalesOrderItem();

            $itemEntity->fromArray($item->toArray());

            $itemEntity->setQuantity($quantity);

            $itemEntity->setFkSalesOrder($orderEntity->getIdSalesOrder());
            $itemEntity->setFkOmsOrderItemState($this->omsFacade->getInitialStateEntity()
                ->getIdOmsOrderItemState());

            $itemEntity->setProcess($orderProcess);

            $taxSet = $item->getTaxSet();
            if ($taxSet !== null) {
                $itemEntity->setTaxPercentage($taxSet->getEffectiveRate());
            }

            $itemEntity->save();

            $item->setIdSalesOrderItem($itemEntity->getIdSalesOrderItem());

            // @todo: Illegal direct dependency on ProductOption
            $this->saveProductOptions($item);
        }
    }

    /**
     * @param AddressTransfer|null $address
     *
     * @return SpySalesOrderAddress|null
     */
    protected function saveAddressTransfer(AddressTransfer $address)
    {
        if ($address === null) {
            return null;
        }

        $addressEntity = new SpySalesOrderAddress();
        $addressEntity->fromArray($address->toArray());
        $addressEntity->setFkCountry($this->countryFacade->getIdCountryByIso2Code($address->getIso2Code()));

        $addressEntity->save();
        $address->setIdSalesOrderAddress($addressEntity->getIdSalesOrderAddress());

        return $addressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return void
     */
    protected function saveProductOptions(ItemTransfer $item)
    {
        foreach ($item->getProductOptions() as $productOptionTransfer) {
            $optionEntity = new SpySalesOrderItemOption();

            $optionEntity->fromArray($productOptionTransfer->toArray());

            $optionEntity->setFkSalesOrderItem($item->getIdSalesOrderItem());
            $optionEntity->setTaxPercentage($productOptionTransfer->getTaxSet()
                ->getEffectiveRate());

            $optionEntity->save();

            $productOptionTransfer->setIdSalesOrderItemOption($optionEntity->getIdSalesOrderItemOption());
        }
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

        return (new OrderTransfer())->fromArray($orderEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
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

}
