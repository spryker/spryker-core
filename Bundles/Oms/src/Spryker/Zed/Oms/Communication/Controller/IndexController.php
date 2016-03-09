<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Controller;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacade getFacade()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{

    const DEFAULT_FORMAT = 'svg';
    const DEFAULT_FONT_SIZE = '14';

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function drawAction(Request $request)
    {
        $processName = $request->query->get('process'); // TODO FW Validation
        if ($processName === null) {
            return $this->redirectResponse('/oms');
        }

        $format = $request->query->get('format'); // TODO FW Validation
        $fontSize = $request->query->getInt('font'); // TODO FW Validation
        $highlightState = $request->query->get('state'); // TODO FW Validation

        $reload = false;
        if ($format === null) {
            $format = self::DEFAULT_FORMAT;
            $reload = true;
        }
        if ($fontSize === 0) {
            $fontSize = self::DEFAULT_FONT_SIZE;
            $reload = true;
        }

        if ($reload) {
            return $this->redirectResponse('/oms/index/draw?process=' . $processName . '&format=' . $format . '&font=' . $fontSize.'&state='.$highlightState);
        }

        $response = $this->getFacade()->drawProcess($processName, $highlightState, $format, $fontSize);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function drawItemAction(Request $request)
    {
        $id = $this->castId($request->query->get('id'));

        $format = $request->query->get('format', self::DEFAULT_FORMAT); // TODO FW Validation
        $fontSize = $request->query->getInt('font', self::DEFAULT_FONT_SIZE); // TODO FW Validation

        $orderItem = SpySalesOrderItemQuery::create()->findOneByIdSalesOrderItem($id);
        $processEntity = $orderItem->getProcess();

        echo $this->getFacade()->drawProcess($processEntity->getName(), $orderItem->getState()->getName(), $format, $fontSize);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function drawPreviewVersionAction(Request $request)
    {
        $processName = $request->query->get('process'); // TODO FW Validation
        if ($processName === null) {
            return $this->redirectResponse('/oms');
        }

        return $this->viewResponse([
            'processName' => $processName,
        ]);
    }

}
