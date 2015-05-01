<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesBusiness;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesExpense;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class OrderBuilder
{

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @var SalesBusiness
     */
    protected $factory;

    /**
     * @param LocatorLocatorInterface $locator
     * @param FactoryInterface $factory
     */
    public function __construct(LocatorLocatorInterface $locator, FactoryInterface $factory)
    {
        $this->locator = $locator;
        $this->factory = $factory;
    }

    /**
     * @param Order $transferOrder
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    public function createOrderEntityWithoutIncrementId(Order $transferOrder)
    {
        $order = new SpySalesOrder();
        $order->fromArray($transferOrder->toArray());

        $this->addIsTest($transferOrder, $order);
        $this->addOrderAddresses($transferOrder, $order);
        $this->addCustomer($transferOrder, $order);
        $this->addOrderItems($transferOrder, $order);
        $this->addExpenses($transferOrder, $order);

        $order->setGrandTotal($order->getTotals()->getGrandTotalWithDiscounts());
        $order->setSubtotal($order->getTotals()->getSubtotal());

        return $order;
    }

    /**
     * @param Order $transferOrder
     * @return bool
     */
    protected function isTestOrder(Order $transferOrder)
    {
        if ($transferOrder->getIsTest()) {
            return true;
        }

        $testNames = array(
            'test',
            'test2'
        );
        $checkedNames = array(
            mb_strtolower($transferOrder->getFirstName()),
            mb_strtolower($transferOrder->getLastName()),
            mb_strtolower($transferOrder->getShippingAddress()->getFirstName()),
            mb_strtolower($transferOrder->getShippingAddress()->getLastName())
        );

        $customer = $transferOrder->getCustomer();
        if (!empty($customer) && !$customer->isEmpty()) {
            $checkedNames[] = mb_strtolower($customer->getFirstName());
            $checkedNames[] = mb_strtolower($customer->getLastName());
        }

        foreach ($testNames as $testName) {
            foreach ($checkedNames as $checkName) {
                if ($testName === $checkName) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param Order $transferOrder
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    protected function addIsTest(Order $transferOrder, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $order->setIsTest($this->isTestOrder($transferOrder));

        return $order;
    }

    /**
     * @param Order $transferOrder
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    protected function addCustomer(Order $transferOrder, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $customer = $transferOrder->getCustomer();
        if (!empty($customer) && !$customer->isEmpty()) {
            $order->setFkCustomer($customer->getIdCustomer());
            $order->setEmail($customer->getEmail());
        }

        return $order;
    }

    /**
     * @param Order $transferOrder
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    protected function addExpenses(Order $transferOrder, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        foreach ($transferOrder->getExpenses() as $expense) {
            $entity = new SpySalesExpense();
            $entity->fromArray($expense->toArray());
            $order->addExpense($entity);
            $this->locator->salesrule()->facade()->addDiscountsToDiscountableItem($entity, $expense->getDiscounts());
        }

        return $order;
    }

    /**
     * @param Order $transferOrder
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    protected function addOrderAddresses(Order $transferOrder, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $billingAddress = new SpySalesOrderAddress();
        $billingAddress->fromArray($transferOrder->getBillingAddress()->toArray(false));
        $order->setBillingAddress($billingAddress);

        $shippingAddress = new SpySalesOrderAddress($transferOrder->getShippingAddress()->toArray(false));
        $shippingAddress->fromArray($transferOrder->getShippingAddress()->toArray(false));
        $order->setShippingAddress($shippingAddress);

        return $order;
    }

    /**
     * @param Order $transferOrder
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     * @throws \LogicException
     */
    protected function addOrderItems(Order $transferOrder, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        /* @var $transferItem OrderItem */
        foreach ($transferOrder->getItems() as $transferItem) {
            $itemBuilder = $this->getItemBuilder($transferItem);
            $itemEntity = $itemBuilder->createOrderItemEntity($transferItem, $transferOrder);
            if ($itemEntity instanceof \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem) {
                $order->addItem($itemEntity);
            } elseif($itemEntity instanceof \Propel\Runtime\Collection\Collection) {
                foreach($itemEntity as $createdItem) {
                    $order->addItem($createdItem);
                }
            }
        }
        return $order;
    }

    /**
     * FIXME FIXME FIXME
     * @param OrderItem $orderItem
     * @return OrderItemBuilder|DefaultOrderNonSplitBundleItemBuilder|DefaultOrderSplitBundleItemBuilder
     * @throws \LogicException
     */
    protected function getItemBuilder(OrderItem $orderItem)
    {
        $variety = $orderItem->getVariety();
        if ($variety === 'bundle') {
//            $bundleType = $this->locator->product()->facade()->getBundleTypeBySku($orderItem->getSku());
//            switch($bundleType) {
//                case \SprykerFeature\Zed\Catalog\Persistence\Propel\Map\PacCatalogProductBundleTableMap::COL_BUNDLE_TYPE_SPLITBUNDLE :
//                    return $this->factory->createModelDefaultOrderSplitBundleItemBuilder(
//                        $this->locator,
//                        $this->factory
//                    );
//                    break;
//                case PacCatalogProductBundleTableMap::COL_BUNDLE_TYPE_NONSPLITBUNDLE :
//                    return $this->factory->createModelDefaultOrderNonSplitBundleItemBuilder(
//                        $this->locator,
//                        $this->factory
//                    );
//                    break;
//                default :
            throw new \LogicException('Bundles are not supported right now!');
//                    break;
//            }
        } else {
            return $this->factory->createModelOrderItemBuilder($this->locator, $this->factory);
        }
    }

}
