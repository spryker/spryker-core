<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStateQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use Symfony\Component\HttpFoundation\Request;

class SandboxController extends AbstractController
{

    /**
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
    }

    public function createOrderAction()
    {

        Propel::getConnection()->beginTransaction();

        $country = SpyCountryQuery::create()->findOne();

        $state = SpyOmsOrderItemStateQuery::create()->findOneByName('new');
        if ($state === null) {
            $state = new SpyOmsOrderItemState();
            $state->setName('new');
            $state->save();
        }

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setSalutation('Mr');
        $customerTransfer->setFirstName('John');
        $customerTransfer->setLastName('Doe');
        $customerTransfer->setEmail('john.doe@spryker.com');

        $address = new SpySalesOrderAddress();
        $address->setFirstName($customerTransfer->getFirstName());
        $address->setLastName($customerTransfer->getLastName());
        $address->setAddress1('Teststr.');
        $address->setZipCode(10115);
        $address->setCity('Berlin');
        $address->setCountry($country);
        $address->save();

        $total = rand(10, 1000);

        $order = new SpySalesOrder();
        $order->setIsTest(true);
        $order->setShippingAddress($address);
        $order->setBillingAddress($address);
        $order->setSubtotal($total);
        $order->setGrandTotal($total);
        $order->setFirstName($customerTransfer->getFirstName());
        $order->setLastName($customerTransfer->getLastName());
        $order->setEmail($customerTransfer->getEmail());
        $order->setSalutation($customerTransfer->getSalutation());
        $order->setOrderReference(uniqid('testorder_'));
        $order->save();

        $process = SpyOmsOrderProcessQuery::create()->findOneByName('Nopayment01');
        if ($process === null) {
            $process = new SpyOmsOrderProcess();
            $process->setName('Nopayment01');
            $process->save();
        }

        $orderItem = new SpySalesOrderItem();
        $orderItem->setOrder($order);
        $orderItem->setState($state);
        $orderItem->setProcess($process);
        $orderItem->setGrossPrice($total / 2);
        $orderItem->setPriceToPay($total / 2);
        $orderItem->setSku('42');
        $orderItem->setName('1. item');
        $orderItem->save();

        $orderItem = new SpySalesOrderItem();
        $orderItem->setOrder($order);
        $orderItem->setState($state);
        $orderItem->setProcess($process);
        $orderItem->setGrossPrice($total / 2);
        $orderItem->setPriceToPay($total / 2);
        $orderItem->setSku('43');
        $orderItem->setName('2. item');
        $orderItem->save();

        Propel::getConnection()->commit();

        die('<pre><b>' . print_r('!!!', true) . '</b>' . PHP_EOL . __CLASS__ . ' ' . __LINE__);
    }

}
