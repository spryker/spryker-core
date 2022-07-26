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
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    public const DEFAULT_FORMAT = 'svg';

    /**
     * @var int
     */
    public const DEFAULT_FONT_SIZE = 14;

    /**
     * @uses \Spryker\Zed\Oms\Communication\Controller\IndexController::indexAction()
     *
     * @var string
     */
    protected const URL_OMS = '/oms';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FORMAT_NOT_SUPPORTED = 'This file format is not supported. Please use file format SVG.';

    /**
     * @var array<string, string>
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
        /** @var string|null $processName */
        $processName = $request->query->get('process');
        if ($processName === null) {
            return $this->redirectResponse(static::URL_OMS);
        }

        /** @var string|null $format */
        $format = $request->query->get('format');
        $fontSize = $request->query->getInt('font');

        /** @var string|null $highlightState */
        $highlightState = $request->query->get('state');

        $reload = false;
        if ($format === null) {
            $format = static::DEFAULT_FORMAT;
            $reload = true;
        }
        if ($fontSize === 0) {
            $fontSize = static::DEFAULT_FONT_SIZE;
            $reload = true;
        }

        if ($reload) {
            return $this->redirectResponse('/oms/index/draw?process=' . $processName . '&format=' . $format . '&font=' . $fontSize . '&state=' . $highlightState);
        }

        if (!isset($this->formatContentTypes[$format])) {
            $this->addErrorMessage(static::ERROR_MESSAGE_FORMAT_NOT_SUPPORTED);

            return $this->redirectResponse(static::URL_OMS);
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

        /** @var string $format */
        $format = $request->query->get('format', static::DEFAULT_FORMAT);
        $fontSize = $request->query->getInt('font', static::DEFAULT_FONT_SIZE);

        $orderItem = SpySalesOrderItemQuery::create()->findOneByIdSalesOrderItem($id);
        $processEntity = $orderItem->getProcess();

        return new Response(
            $this->getFacade()->drawProcess($processEntity->getName(), $orderItem->getState()->getName(), $format, $fontSize),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function drawPreviewVersionAction(Request $request)
    {
        $processName = $request->query->get('process');
        if ($processName === null) {
            return $this->redirectResponse(static::URL_OMS);
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
