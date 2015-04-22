<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class WidgetsController extends AbstractController
{

    /**
     * @param Request $request
     * @return array
     */
    public function customerDataAction(Request $request)
    {
        $orderId = $request->query->get('id_sales_order');
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);

        return $this->viewResponse([
            'order' => $order,
            'billingAddress' => $order->getBillingAddress(),
            'shippingAddress' => $order->getShippingAddress()
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function orderInfoAction(Request $request)
    {
        $orderId = $request->query->get('id_sales_order');
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);

        return $this->viewResponse([
            'order' => $order,
            'totals' => $order->getTotals()
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function paymentInfoAction(Request $request)
    {
        $orderId = $request->query->get('id_sales_order');
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);

        return $this->viewResponse([
            'order' => $order
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function stateMachineAction(Request $request)
    {
        $orderId = $request->query->get('id_sales_order');
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);

        $groupedEventList = $this->facadeOms->getGroupedManuallyExecutableEvents($order);

        return $this->viewResponse([
            'order' => $order,
            'orderEvents' => $groupedEventList['order_events']
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function activityLogAction(Request $request)
    {
        $orderId = $request->query->get('id_sales_order');
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);

        $activityLog = $this->facadeOms->getLogForOrder($order);

        $groupBy = array();
        $groupedLogItems = array();
        foreach ($activityLog as $log) {
            $key = $this->createKey($log);
            $groupedLogItems = $this->groupByKey($groupedLogItems, $key, $log);
            $groupBy[$log->getIdOmsTransitionLog()] = $key;
        }

        $logArray = $this->createReducedLogItems($activityLog, $groupBy);

        return $this->viewResponse([
            'order' => $order,
            'groupBy' => $groupBy,
            'groupedLogItems' => $groupedLogItems,
            'activityLog' => $logArray
        ]);
    }

    /**
     * @param \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLog $log
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function createKey(\SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLog $log)
    {
        $userName = '';
        $user = $log->getAclUser();
        if (isset($user)) {
            $userName = $user->getUsername();
        }

        $key = md5($log->getSourceState()
            . $log->getTargetState()
            . $log->getCreatedAt()
            . $log->getHostname()
            . $userName
            . implode('', $log->getCommands())
            . implode('', $log->getConditions())
            . $log->getError()
            . $log->getEvent()
            . $log->getController()
            . $log->getModule()
            . $log->getOrderItem()->getSku()
            . $log->getErrorMessage()
            . $log->getAction()
            . implode('', $log->getParams())
            . $log->getLocked());

        return $key;
    }

    /**
     * @param $buffer
     * @param $key
     * @param $log
     * @return mixed
     */
    protected function groupByKey($buffer, $key, $log)
    {
        if (!isset($buffer[$key])) {
            $buffer[$key] = array($log->getFkSalesOrderItem());
            return $buffer;
        } else {
            $buffer[$key][] = $log->getFkSalesOrderItem();
            return $buffer;
        }
    }

    /**
     * @param $activityLog
     * @param $groupBy
     * @return array
     */
    protected function createReducedLogItems($activityLog, $groupBy)
    {
        $logArray = array();
        foreach ($activityLog as $log) {
            $logArray[$groupBy[$log->getIdOmsTransitionLog()]] = $log;
        }
        return $logArray;
    }


}
