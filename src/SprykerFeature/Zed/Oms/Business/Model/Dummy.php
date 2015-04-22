<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

class Dummy implements DummyInterface
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $processName
     * @return array
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function prepareItems($processName)
    {
        $orderItemsArray = $this->getOrderItems($processName);

        $orders = array();

        $txtArray = array();
        foreach ($orderItemsArray as $orderItemArray) {
            if (!isset($orders[$orderItemArray['orderId']])) {
                $order = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder();

                $order->setGrandTotal(10000);
                $order->setSubtotal(9900);
                $order->setIsTest(false);

                $address = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress();
                $address->setLastName('Doe');
                $address->setFirstName('John');
                $address->setCity('Berlin');
                $address->setZipCode('12345');
                $address->setAddress1('Blastr 1');

                $country = \SprykerFeature\Zed\Misc\Persistence\Propel\SpyMiscCountryQuery::create()->findOneByIdMiscCountry(1);
                $address->setCountry($country);

                $order->setBillingAddress($address);
                $order->setShippingAddress($address);

                $orders[$orderItemArray['orderId']] = $order;
            }
        }

        $statuses = array();

        $orderItems = array();
        foreach ($orderItemsArray as $orderItemArray) {
            if (isset($statuses[$orderItemArray['status']])) {
                $status = $statuses[$orderItemArray['status']];
            } else {
                $status = new \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatus();
                $status->setName($orderItemArray['status']);
                $status->save();
                $statuses[$orderItemArray['status']] = $status;
            }

            $txtArray[] = 'Status: ' . $status->getName();

            $process = \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery::create()->filterByName($orderItemArray['process'])->findOneOrCreate();
            $process->setName($orderItemArray['process']);
            $process->save();
            $txtArray[] = 'Process: ' . $process->getName();

            $item = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem();
            $item->setStatus($status);
            $item->setProcess($process);

            $item->setName('Testproduct');
            $item->setSku('12345ABC');
            $item->setGrossPrice(10);
            $item->setPriceToPay(5);
            $item->setVariety('Single');

            $orders[$orderItemArray['orderId']]->addItem($item);

            $orderItems[] = $item;
        }

        foreach ($orderItems as $orderItem) {
            $orderItem->save();
            $txtArray[] = 'orderItem saved: ' . $orderItem->getIdSalesOrderItem();
        }

        return $txtArray;
    }

    /**
     * @param string $processName
     * @return array
     */
    public function getOrderItems($processName)
    {

        $orderItemsArray = array();
        $c = 0;
        $process = $this->builder->createProcess($processName);
        for ($i = 0; $i < 2; $i++) {
            foreach ($process->getAllStatuses() as $status) {
                $orderItemsArray[] = array(
                    'id' => $c,
                    'process' => $processName,
                    'status' => $status->getName(),
                    'orderId' => $i
                );
                $c++;
break 2;

            }

        }

        return $orderItemsArray;
    }

}
