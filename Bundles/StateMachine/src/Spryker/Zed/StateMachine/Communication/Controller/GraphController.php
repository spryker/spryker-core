<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Controller;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacade getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 */
class GraphController extends AbstractController
{

    const DEFAULT_FORMAT = 'svg';
    const DEFAULT_FONT_SIZE = '14';

    /**
     * @var array
     */
    protected $formatContentTypes = [
        'jpg' => 'image/jpeg',
        'svg' => 'image/svg+xml',
    ];

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function drawAction(Request $request)
    {
        $processName = $request->query->get('process');
        if ($processName === null) {
            return $this->redirectResponse('/state-machine/list');
        }

        $format = $request->query->get('format');
        $fontSize = $request->query->getInt('font');
        $highlightState = $request->query->get('state');
        $stateMachine = $request->query->get('state-machine');

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
            return $this->redirectResponse(
                '/state-machine/graph/draw?process=' . $processName . '&format=' . $format . '&font=' . $fontSize.'&state='.$highlightState. '&state-machine='. $stateMachine
            );
        }

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setStateMachineName($stateMachine);
        $stateMachineProcessTransfer->setProcessName($processName);

        $response = $this->getFacade()->drawProcess($stateMachineProcessTransfer, $highlightState, $format, $fontSize);

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
        $stateMachine = $request->query->get('state-machine');
        $processName = $request->query->get('process-name');
        $highlightState = $request->query->get('highlight-state');

        $format = $request->query->get('format', self::DEFAULT_FORMAT);
        $fontSize = $request->query->getInt('font', self::DEFAULT_FONT_SIZE);

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
        $processName = $request->query->get('process');
        if ($processName === null) {
            return $this->redirectResponse('/state-machine/list');
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
