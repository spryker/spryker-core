<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Controller;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 */
class GraphController extends AbstractController
{
    public const URL_PARAM_PROCESS = 'process';
    public const URL_PARAM_FORMAT = 'format';
    public const URL_PARAM_FONT_SIZE = 'font';
    public const URL_PARAM_HIGHLIGHT_STATE = 'highlight-state';
    public const URL_PARAM_STATE_MACHINE = 'state-machine';
    public const URL_STATE_MACHINE_LIST = '/state-machine/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function drawAction(Request $request)
    {
        $processName = $request->query->get(self::URL_PARAM_PROCESS);
        if ($processName === null) {
            return $this->redirectResponse(self::URL_STATE_MACHINE_LIST);
        }

        $format = $request->query->get(self::URL_PARAM_FORMAT);
        $fontSize = $request->query->getInt(self::URL_PARAM_FONT_SIZE);
        $highlightState = $request->query->get(self::URL_PARAM_HIGHLIGHT_STATE);
        $stateMachine = $request->query->get(self::URL_PARAM_STATE_MACHINE);

        $reload = false;
        $stateMachineBundleConfig = $this->getFactory()->getConfig();
        if ($format === null) {
            $format = $stateMachineBundleConfig->getGraphDefaultFormat();
            $reload = true;
        }
        if ($fontSize === 0) {
            $fontSize = $stateMachineBundleConfig->getGraphDefaultFontSize();
            $reload = true;
        }

        if ($reload) {
            return $this->redirectResponse(
                Url::generate(
                    '/state-machine/graph/draw',
                    [
                        self::URL_PARAM_PROCESS => $processName,
                        self::URL_PARAM_FORMAT => $format,
                        self::URL_PARAM_FONT_SIZE => $fontSize,
                        self::URL_PARAM_HIGHLIGHT_STATE => $highlightState,
                        self::URL_PARAM_STATE_MACHINE => $stateMachine,
                    ]
                )->build()
            );
        }

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setStateMachineName($stateMachine);
        $stateMachineProcessTransfer->setProcessName($processName);

        $response = $this->getFacade()->drawProcess($stateMachineProcessTransfer, $highlightState, $format, $fontSize);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse(
            $callback,
            Response::HTTP_OK,
            $this->getStreamedResponseHeaders($format)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function drawItemAction(Request $request)
    {
        $stateMachine = $request->query->get(self::URL_PARAM_STATE_MACHINE);
        $processName = $request->query->get(self::URL_PARAM_PROCESS);
        $highlightState = $request->query->get(self::URL_PARAM_HIGHLIGHT_STATE);

        $stateMachineBundleConfig = $this->getFactory()->getConfig();
        $format = $request->query->get(self::URL_PARAM_FORMAT, $stateMachineBundleConfig->getGraphDefaultFormat());
        $fontSize = $request->query->getInt(self::URL_PARAM_FONT_SIZE, $stateMachineBundleConfig->getGraphDefaultFontSize());

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setStateMachineName($stateMachine);
        $stateMachineProcessTransfer->setProcessName($processName);

        return new Response(
            $this->getFacade()->drawProcess($stateMachineProcessTransfer, $highlightState, $format, $fontSize)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function drawPreviewVersionAction(Request $request)
    {
        $processName = $request->query->get(self::URL_PARAM_PROCESS);
        if ($processName === null) {
            return $this->redirectResponse(self::URL_STATE_MACHINE_LIST);
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
        $stateMachineBundleConfig = $this->getFactory()->getConfig();
        $formatContentTypes = $stateMachineBundleConfig->getGraphFormatContentTypes();
        if (isset($formatContentTypes[$format])) {
            $headers['content-type'] = $formatContentTypes[$format];
        }

        return $headers;
    }
}
