<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
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
        $fontSize = $request->query->get('font');
        $highlightState = $request->query->get('state');

        $reload = false;
        if (is_null($format)) {
            $format = 'gif';
            $reload = true;
        }
        if (is_null($fontSize)) {
            $fontSize = '14';
            $reload = true;
        }

        if ($reload) {
            return $this->redirectResponse('/oms/index/draw?process=' . $processName . '&format=' . $format . '&font=' . $fontSize);
        }

        $response = $this->getFacade()->drawProcess($processName, $highlightState, $format, $fontSize);

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
        $fontSize = $request->query->get('font', '14');

        $orderItem = SpySalesOrderItemQuery::create()->findOneByIdSalesOrderItem($id);
        $processEntity = $orderItem->getProcess();

        echo $this->getFacade()->drawProcess($processEntity->getName(), $orderItem->getState()->getName()), $format, $fontSize;
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

}
