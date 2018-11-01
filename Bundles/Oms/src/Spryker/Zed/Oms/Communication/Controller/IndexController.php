<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Controller;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{
    public const DEFAULT_FORMAT = 'svg';
    public const DEFAULT_FONT_SIZE = 14;

    /**
     * @var array
     */
    protected $formatContentTypes = [
        'jpg' => 'image/jpeg',
        'svg' => 'image/svg+xml',
    ];

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function drawAction(Request $request)
    {
        $processName = $request->query->get('process');
        if ($processName === null) {
            return $this->redirectResponse('/oms');
        }

        $format = $request->query->get('format');
        $fontSize = $request->query->getInt('font');
        $highlightState = $request->query->get('state');

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
            return $this->redirectResponse('/oms/index/draw?process=' . $processName . '&format=' . $format . '&font=' . $fontSize . '&state=' . $highlightState);
        }

        $response = $this->getFacade()->drawProcess($processName, $highlightState, $format, $fontSize);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback, Response::HTTP_OK, $this->getStreamedResponseHeaders($format));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function drawItemAction(Request $request)
    {
        $id = $this->castId($request->query->get('id'));

        $format = $request->query->get('format', self::DEFAULT_FORMAT);
        $fontSize = $request->query->getInt('font', self::DEFAULT_FONT_SIZE);

        $orderItem = SpySalesOrderItemQuery::create()->findOneByIdSalesOrderItem($id);
        $processEntity = $orderItem->getProcess();

        return new Response(
            $this->getFacade()->drawProcess($processEntity->getName(), $orderItem->getState()->getName(), $format, $fontSize)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function drawPreviewVersionAction(Request $request)
    {
        $processName = $request->query->get('process');
        if ($processName === null) {
            return $this->redirectResponse('/oms');
        }

        return $this->viewResponse([
            'processName' => $processName,
        ]);
    }

    /**
     * @param string $format
     *
     * @return array
     */
    protected function getStreamedResponseHeaders($format)
    {
        $headers = [];

        if (isset($this->formatContentTypes[$format])) {
            $headers['content-type'] = $this->formatContentTypes[$format];
        }

        return $headers;
    }
}
