<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use Propel\Runtime\Exception\PropelException;
use Exception;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery;

// FIXME core-120 move queries to queryContainer
class Dummy implements DummyInterface
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @param BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $processName
     *
     * @throws Exception
     * @throws PropelException
     *
     * @return array
     */
    public function prepareItems($processName)
    {
        $orderItemsArray = $this->getOrderItems($processName);

        $orders = [];

        $txtArray = [];
        foreach ($orderItemsArray as $orderItemArray) {
            if (!isset($orders[$orderItemArray['orderId']])) {
                $order = new SpySalesOrder();

                $order->setGrandTotal(10000);
                $order->setSubtotal(9900);
                $order->setIsTest(false);

                $address = new SpySalesOrderAddress();
                $address->setLastName('Doe');
                $address->setFirstName('John');
                $address->setCity('Berlin');
                $address->setZipCode('12345');
                $address->setAddress1('Blastr 1');

                $country = SpyCountryQuery::create()->findOneByIdCountry(1);
                $address->setCountry($country);

                $order->setBillingAddress($address);
                $order->setShippingAddress($address);

                $orders[$orderItemArray['orderId']] = $order;
            }
        }

        $states = [];

        $orderItems = [];
        foreach ($orderItemsArray as $orderItemArray) {
            if (isset($states[$orderItemArray['state']])) {
                $state = $states[$orderItemArray['state']];
            } else {
                $state = new SpyOmsOrderItemState();
                $state->setName($orderItemArray['state']);
                $state->save();
                $states[$orderItemArray['state']] = $state;
            }

            $txtArray[] = 'State: ' . $state->getName();

            $process = SpyOmsOrderProcessQuery::create()->filterByName($orderItemArray['process'])->findOneOrCreate();
            $process->setName($orderItemArray['process']);
            $process->save();
            $txtArray[] = 'Process: ' . $process->getName();

            $item = new SpySalesOrderItem();
            $item->setState($state);
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
     *
     * @return array
     */
    public function getOrderItems($processName)
    {
        $orderItemsArray = [];
        $c = 0;
        $process = $this->builder->createProcess($processName);
        for ($i = 0; $i < 2; $i++) {
            foreach ($process->getAllStates() as $state) {
                $orderItemsArray[] = [
                    'id' => $c,
                    'process' => $processName,
                    'state' => $state->getName(),
                    'orderId' => $i,
                ];
                $c++;
                break 2;
            }
        }

        return $orderItemsArray;
    }

}
