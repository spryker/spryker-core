<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method OmsFacade getFacade()
 * @method OmsQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'processes' => $this->getFacade()->getProcesses(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function drawAction(Request $request)
    {
        $processName = $request->query->get('process');
        if (is_null($processName)) {
            return $this->redirectResponse('/oms');
        }

        $format = $request->query->get('format');
        $fontsize = $request->query->get('font');

        $reload = false;
        if (is_null($format)) {
            $format = 'gif';
            $reload = true;
        }
        if (is_null($fontsize)) {
            $fontsize = '14';
            $reload = true;
        }

        if ($reload) {
            return $this->redirectResponse('/oms/index/draw?process=' . $processName . '&format=' . $format . '&font=' . $fontsize);
        }

        $response = $this->getFacade()->drawProcess($processName, null, $format, $fontsize);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param Request $request
     */
    public function drawItemAction(Request $request)
    {
        $id = $request->query->get('id');

        $format = $request->query->get('format', 'gif');
        $fontsize = $request->query->get('font', '14');

        $orderItem = SpySalesOrderItemQuery::create()->findOneByIdSalesOrderItem($id);
        $processEntity = $orderItem->getProcess();

        echo $this->getFacade()->drawProcess($processEntity->getName(), $orderItem->getState()->getName()), $format, $fontsize;
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function drawPreviewVersionAction(Request $request)
    {
        $processName = $request->query->get('process');
        if (is_null($processName)) {
            return $this->redirectResponse('/oms');
        }

        return $this->viewResponse([
            'processName' => $processName,
        ]);
    }

    /**
     * @param Request $request
     *
     * @throws \Exception
     */
    public function debugAction(Request $request)
    {
        $processName = $request->query->get('process', 'PayonePaypal01');

        $orderItems = SpySalesOrderItemQuery::create()->find();

        echo '<div style="float:right">';

        echo '<iframe src="/oms/index/draw/process/"' . $processName . ' width="1000" height="100%"></iframe>';

        echo '</div>';

        echo '<table border="1">';
        echo '<tr>';
        echo '<td><b>Order</td>';
        echo '<td><b>OrderItem</td>';
        echo '<td><b>Process</td>';
        echo '<td><b>State</td>';
        echo '<td><b>Events</td>';
        echo '</tr>';

        foreach ($orderItems as $orderItem) {
            $process = $this->getFacade()->getProcess($orderItem->getProcess()->getName());
            $events = $process->getStateFromAllProcesses($orderItem->getState()->getName())->getEvents();

            /* @var SpySalesOrderItem $orderItem */
            echo '<tr>';
            echo '<td><a href="/sales/order-details/activity-log?id_sales_order=' . $orderItem->getOrder()->getIdSalesOrder() . '">' . $orderItem->getOrder()->getIdSalesOrder() . '</a></td>';
            echo '<td>' . $orderItem->getIdSalesOrderItem() . '</td>';
            echo '<td>' . $orderItem->getProcess()->getName() . '</td>';
            echo '<td>' . $orderItem->getState()->getName() . '</td>';

            echo '<td>';
            foreach ($events as $event) {
                echo '&bull; <a href="/oms/index/trigger?id=' . $orderItem->getIdSalesOrderItem() . '&event=' . $event->getName() . '">' . $event->getName() . '</a><br />';
            }

            echo '</td>';

            echo '</tr>';
        }
        echo '</table>';

        echo '<a href="/oms/index/reset?process=' . $processName . '">reset data</a>';
        echo '<br />';
        echo '<a href="/oms/index/check-conditions?process=' . $processName . '">check conditions</a>';
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function triggerAction(Request $request)
    {
        $processName = $request->query->get('process', 'PayonePaypal01');
        $orderItemId = $request->query->get('id');
        $event = $request->query->get('event');

        $data = [
            'aaa' => 'bbb',
        ];

        $orderItems = SpySalesOrderItemQuery::create()->findByIdSalesOrderItem($orderItemId);
        $this->getFacade()->triggerEvent($event, $orderItems, $data);

        return $this->redirectResponse('/oms/index/index?process=' . $processName);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function checkConditionsAction(Request $request)
    {
        $processName = $request->query->get('process', 'PayonePaypal01');
        $this->getFacade()->checkConditions($processName);

        return $this->redirectResponse('/oms/index/index?process=' . $processName);
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     */
    protected function debug($orderItems)
    {
        foreach ($orderItems as $orderItem) {
            echo $orderItem->getIdSalesOrderItem() . $orderItem->getState()->getName() . '<br />';
        }

        echo '<pre>';
        var_dump('--');
        echo '<hr>';
        echo __FILE__ . ' ' . __LINE__;
        die;
    }

}
