<?php

namespace SprykerFeature\Zed\Oms\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Oms\Persistence\Propel\Base\SpyOmsOrderProcessQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStateQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method OmsFacade getFacade()
 */
class SandboxController extends AbstractController
{

    public function indexAction()
    {
        $orderItems = [];
        foreach (SpySalesOrderItemQuery::create()->find() as $orderItem) {
            $orderItems[] = [
                'id' => $orderItem->getIdSalesOrderItem(),
                'process' => $orderItem->getProcess()->getName(),
                'state' => $orderItem->getState()->getName(),
            ];
        }

        return [
            'orderItems' => $orderItems,
        ];
    }

    public function addAction()
    {
        $this->createOrderItem();

        return $this->redirectResponse('/oms/sandbox/');
    }

    public function triggerAction(Request $request)
    {
        $event = $request->query->get('event');

        $idOrderItem = $request->query->get('id');
        $orderItem = SpySalesOrderItemQuery::create()->findPk($idOrderItem);
        if ($orderItem === null) {
            throw new NotFoundHttpException('Unknown OrderItem Id');
        }

        $this->getFacade()->triggerEventForOneItem($event, $orderItem, []);

        return $this->redirectResponse('/oms/sandbox');
    }

    protected function createOrderItem()
    {
        $country = SpyCountryQuery::create()->findOne();

        $state = SpyOmsOrderItemStateQuery::create()->findOneByName('new');
        if ($state === null) {
            $state = new SpyOmsOrderItemState();
            $state->setName('new');
            $state->save();
        }

        $address = new SpySalesOrderAddress();
        $address->setFirstName('Test');
        $address->setLastName('Test');
        $address->setAddress1('Test');
        $address->setZipCode(0451);
        $address->setCity('Test');
        $address->setCountry($country);
        $address->save();

        $order = new SpySalesOrder();
        $order->setIsTest(true);
        $order->setShippingAddress($address);
        $order->setBillingAddress($address);
        $order->setSubtotal(23);
        $order->setGrandTotal(23);
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
        $orderItem->setGrossPrice(23);
        $orderItem->setPriceToPay(23);
        $orderItem->setSku('42');
        $orderItem->setName('Answering Machine');
        $orderItem->save();
    }
}
