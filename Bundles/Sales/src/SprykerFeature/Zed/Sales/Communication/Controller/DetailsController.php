<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\PayonePaymentDetailTransfer;
use SprykerFeature\Zed\Sales\Communication\Form\PaymentDetailForm;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Business\SalesFacade;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 * @method SalesFacade getFacade()
 * @method SalesQueryContainerInterface getQueryContainer()
 */
class DetailsController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');

        $orderEntity = $this->getQueryContainer()
            ->querySalesOrderById($idOrder)
            ->findOne();

        if ($orderEntity === null) {
            throw new NotFoundHttpException('Record not found');
        }

        $orderItems = $this->getQueryContainer()
            ->querySalesOrderItemsWithState($idOrder)
            ->find();

        $orderItemSplitFormCollection = $this->getDependencyContainer()->getOrderItemSplitFormCollection($orderItems);

        $events = $this->getFacade()->getArrayWithManualEvents($idOrder);
        $allEvents = $this->groupEvents($events);
        $expenses = $this->getQueryContainer()
            ->querySalesExpensesByOrderId($idOrder)
            ->find();
        $shippingAddress = $this->getQueryContainer()
            ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressShipping())
            ->findOne();
        if ($orderEntity->getFkSalesOrderAddressShipping() === $orderEntity->getFkSalesOrderAddressBilling()) {
            $billingAddress = $shippingAddress;
        } else {
            $billingAddress = $this->getQueryContainer()
                ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressBilling())
                ->findOne();
        }

        $logs = $this->getFacade()->getPaymentLogs($idOrder);

        $refunds = $this->getFacade()->getRefunds($idOrder);

        $itemsInProgress = $this->getDependencyContainer()->getOmsFacade()->getItemsWithFlag($orderEntity, 'in progress');
        $itemsPaid = $this->getDependencyContainer()->getOmsFacade()->getItemsWithFlag($orderEntity, 'paid');
        $itemsCancelled = $this->getDependencyContainer()->getOmsFacade()->getItemsWithFlag($orderEntity, 'cancelled');

        /** @var SpyPaymentPayone $paymentPayoneEntity */
        $paymentPayoneEntity = $orderEntity->getSpyPaymentPayones()->getFirst();
        if (null !== $paymentPayoneEntity) {
            $idPayment = $paymentPayoneEntity->getIdPaymentPayone();

            /** @var PaymentDetailForm $form */
            $form = $this->getDependencyContainer()
                ->createPaymentDetailForm($idPayment)
            ;
            $form->handleRequest();

            if ($form->isValid()) {
                $paymentDetailTransfer = (new PayonePaymentDetailTransfer())->fromArray($form->getData(), true);
                $this->getFacade()
                    ->updatePaymentDetail($paymentDetailTransfer, $idPayment)
                ;

                return $this->redirectResponse(sprintf('/sales/details/?id-sales-order=%d', $idOrder));
            }
        }

        return [
            'idOrder' => $idOrder,
            'orderDetails' => $orderEntity,
            'orderItems' => $orderItems,
            'events' => $events,
            'allEvents' => $allEvents,
            'expenses' => $expenses,
            'logs' => $logs,
            'refunds' => $refunds,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection->create(),
            'itemsInProgress' => $itemsInProgress,
            'itemsPaid' => $itemsPaid,
            'itemsCancelled' => $itemsCancelled,
            'form' => isset($form) ? $form->createView() : null,
        ];
    }

    /**
     * @param $events
     *
     * @return array
     */
    protected function groupEvents($events)
    {
        $allEvents = [];
        foreach ($events as $eventList) {
            $allEvents = array_merge($allEvents, $eventList);
        }

        return array_unique($allEvents);
    }

}
