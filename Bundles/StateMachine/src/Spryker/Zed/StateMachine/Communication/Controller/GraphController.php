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
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineRepositoryInterface getRepository()
 */
class GraphController extends AbstractController
{
    /**
     * @var string
     */
    public const URL_PARAM_PROCESS = 'process';

    /**
     * @var string
     */
    public const URL_PARAM_FORMAT = 'format';

    /**
     * @var string
     */
    public const URL_PARAM_FONT_SIZE = 'font';

    /**
     * @var string
     */
    public const URL_PARAM_HIGHLIGHT_STATE = 'highlight-state';

    /**
     * @var string
     */
    public const URL_PARAM_STATE_MACHINE = 'state-machine';

    /**
     * @var string
     */
    public const URL_STATE_MACHINE_LIST = '/state-machine/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function drawAction(Request $request)
    {
        $processName = (string)$request->query->get(static::URL_PARAM_PROCESS) ?: null;
        if ($processName === null) {
            return $this->redirectResponse(static::URL_STATE_MACHINE_LIST);
        }

        $format = (string)$request->query->get(static::URL_PARAM_FORMAT) ?: null;
        $fontSize = $request->query->getInt(static::URL_PARAM_FONT_SIZE);
        $highlightState = (string)$request->query->get(static::URL_PARAM_HIGHLIGHT_STATE);
        $stateMachine = (string)$request->query->get(static::URL_PARAM_STATE_MACHINE);

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
                        static::URL_PARAM_PROCESS => $processName,
                        static::URL_PARAM_FORMAT => $format,
                        static::URL_PARAM_FONT_SIZE => $fontSize,
                        static::URL_PARAM_HIGHLIGHT_STATE => $highlightState,
                        static::URL_PARAM_STATE_MACHINE => $stateMachine,
                    ],
                )->build(),
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
            $this->getStreamedResponseHeaders($format),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function drawItemAction(Request $request)
    {
        /** @var string|null $stateMachine */
        $stateMachine = $request->query->get(static::URL_PARAM_STATE_MACHINE);
        /** @var string|null $processName */
        $processName = $request->query->get(static::URL_PARAM_PROCESS);
        /** @var string|null $highlightState */
        $highlightState = $request->query->get(static::URL_PARAM_HIGHLIGHT_STATE);

        $stateMachineBundleConfig = $this->getFactory()->getConfig();
        /** @var string $format */
        $format = $request->query->get(static::URL_PARAM_FORMAT, $stateMachineBundleConfig->getGraphDefaultFormat());
        $fontSize = $request->query->getInt(static::URL_PARAM_FONT_SIZE, $stateMachineBundleConfig->getGraphDefaultFontSize());

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setStateMachineName($stateMachine);
        $stateMachineProcessTransfer->setProcessName($processName);

        return new Response(
            $this->getFacade()->drawProcess($stateMachineProcessTransfer, $highlightState, $format, $fontSize),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function drawPreviewVersionAction(Request $request)
    {
        $processName = $request->query->get(static::URL_PARAM_PROCESS);
        if ($processName === null) {
            return $this->redirectResponse(static::URL_STATE_MACHINE_LIST);
        }

        $stateMachine = $request->query->get(static::URL_PARAM_STATE_MACHINE);

        return $this->viewResponse([
            'processName' => $processName,
            'stateMachineName' => $stateMachine,
        ]);
    }

    /**
     * @param string $format
     *
     * @return array<string>
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
