<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderDetailsController extends AbstractController
{

    /**
     * @return array
     */
    public function init()
    {
    }

    /**
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $orderId = $request->query->get('id');
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);

        return $this->viewResponse([
            'order' => $order
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function activityLogAction(Request $request)
    {
        $idSalesOrder = $request->query->get('id_sales_order');

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function fireEventAction(Request $request)
    {
        $event = $request->query->get('event');
        $idSalesOrderItem = $request->query->get('id_sales_order_item');
        $orderItemQuery = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery();
        $orderItem = $orderItemQuery->findPk($idSalesOrderItem);
        $this->facadeOms->triggerEventForOneItem($event, $orderItem);

        return $this->redirectResponse('/sales/order-details/index?id=' . $orderItem->getOrder()->getIdSalesOrder() .'&id=' . $orderItem->getFkSalesOrder());
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function fireEventOrderAction(Request $request)
    {
        $event = $request->query->get('event');
        $idSalesOrder = $request->query->get('id_sales_order');
        $orderQuery = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery();
        $order = $orderQuery->findPk($idSalesOrder);
        $this->facadeOms->triggerEvent($event, $order->getItems());

        return $this->redirectResponse('/sales/order-details/index/id/' . $order->getIdSalesOrder());
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function fireEventGroupAction(Request $request)
    {
        $orderId = $request->query->get('order_id', null);
        $event = $request->query->get('event', null);
        $itemIds = $request->query->get('item_ids', null);
        if (!is_array($itemIds) || empty($itemIds)) {
            $this->addMessageError('No order item ids to process!');

            return $this->redirectResponse('/sales/order-details/index/id/' . $orderId);
        }
        if (null === $event) {
            $this->addMessageError('No event name to process!');

            return $this->redirectResponse('/sales/order-details/index/id/' . $orderId);
        }
        $itemIds = array_keys($itemIds);
        $orderItemCollection = $this->facadeSales->getOrderItemsByIds($itemIds);
        $this->facadeOms->triggerEvent($event, $orderItemCollection);

        return $this->redirectResponse('/sales/order-details/index/id/' . $orderId);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function resendInvoiceAction(Request $request)
    {
        throw new \Exception('not working');
        $orderId = $request->query->get('order_id');

        $orderEntity = $this->facadeSales->getOrderBySalesOrderId($orderId);
        $invoiceEntity = $this->facadeInvoice->getInvoiceBySalesOrder($orderEntity);
        $mailTransfer = $this->facadeMail->getResendInvoiceTransfer($orderEntity, $invoiceEntity);
        $result = $this->facadeMail->sendMail($mailTransfer);
        if (true === $result->getStatus()) {
            $this->facadeSales->saveNote('Re-sent invoice successfully', $orderEntity, true);
            $this->addMessageSuccess('Invoice mail sent to customer.');
        } else {
            $this->facadeSales->saveNote('Re-sent invoice failed', $orderEntity, false);
            $this->addMessageError('Not able to send Invoice to cutsomer: ' . $result->getMessage());
        }

        return $this->redirectResponse('/sales/order-details/index/id/' . $orderId);
    }


}
