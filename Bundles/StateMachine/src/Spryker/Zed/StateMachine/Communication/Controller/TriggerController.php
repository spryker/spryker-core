<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Controller;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineRepositoryInterface getRepository()
 */
class TriggerController extends AbstractController
{
    public const URL_PARAM_IDENTIFIER = 'identifier';
    public const URL_PARAM_ID_STATE = 'id-state';
    public const URL_PARAM_ID_PROCESS = 'id-process';
    public const URL_PARAM_STATE_MACHINE_NAME = 'state-machine-name';
    public const URL_PARAM_PROCESS_NAME = 'process-name';
    public const URL_PARAM_REDIRECT = 'redirect';
    public const URL_PARAM_EVENT = 'event';

    public const DEFAULT_REDIRECT_URL = '/state-machine/list';

    protected const ERROR_INVALID_FORM = 'Form is invalid';

    /**
     * @deprecated Use {@link submitTriggerEventForNewItemAction()} instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventForNewItemAction(Request $request)
    {
        trigger_error(
            'This action is deprecated, please use submitTriggerEventForNewItemAction() instead.',
            E_USER_DEPRECATED
        );

        $stateMachineName = $request->query->get(self::URL_PARAM_STATE_MACHINE_NAME);
        $processName = $request->query->get(self::URL_PARAM_PROCESS_NAME);

        $stateMachineProcessTransfer = $this->createStateMachineProcessTransfer($processName, $stateMachineName);

        $identifier = $this->castId($request->query->get(self::URL_PARAM_IDENTIFIER));
        $this->getFacade()->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $redirect = $request->query->get(self::URL_PARAM_REDIRECT, self::DEFAULT_REDIRECT_URL);

        return $this->redirectResponse(htmlentities($redirect));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventForNewItemAction(Request $request)
    {
        $redirect = $request->query->get(static::URL_PARAM_REDIRECT, static::DEFAULT_REDIRECT_URL);

        if (!$this->isValidTriggerEventItemPostRequest($request)) {
            $this->addErrorMessage(static::ERROR_INVALID_FORM);

            return $this->redirectResponse($redirect);
        }

        $processName = $request->query->get(static::URL_PARAM_PROCESS_NAME);
        $stateMachineName = $request->query->get(static::URL_PARAM_STATE_MACHINE_NAME);

        $stateMachineProcessTransfer = $this->createStateMachineProcessTransfer($processName, $stateMachineName);

        $identifier = $this->castId($request->query->get(static::URL_PARAM_IDENTIFIER));
        $this->getFacade()->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        return $this->redirectResponse(htmlentities($redirect));
    }

    /**
     * @deprecated Use {@link submitTriggerEventAction()} instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventAction(Request $request)
    {
        trigger_error(
            'This action is deprecated, please use submitTriggerEventAction() instead.',
            E_USER_DEPRECATED
        );

        $identifier = $this->castId($request->query->get(self::URL_PARAM_IDENTIFIER));
        $idState = $this->castId($request->query->get(self::URL_PARAM_ID_STATE));

        $stateMachineItemTransfer = $this->createStateMachineItemTransfer($identifier, $idState);

        $eventName = $request->query->get(self::URL_PARAM_EVENT);
        $this->getFacade()->triggerEvent($eventName, $stateMachineItemTransfer);

        $redirect = $request->query->get(self::URL_PARAM_REDIRECT, self::DEFAULT_REDIRECT_URL);

        return $this->redirectResponse(htmlentities($redirect));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventAction(Request $request)
    {
        $redirect = $request->query->get(static::URL_PARAM_REDIRECT, static::DEFAULT_REDIRECT_URL);

        if (!$this->isValidTriggerEventPostRequest($request)) {
            $this->addErrorMessage(static::ERROR_INVALID_FORM);

            return $this->redirectResponse($redirect);
        }

        $identifier = $this->castId($request->query->get(static::URL_PARAM_IDENTIFIER));
        $idState = $this->castId($request->query->get(static::URL_PARAM_ID_STATE));

        $stateMachineItemTransfer = $this->createStateMachineItemTransfer($identifier, $idState);

        $eventName = $request->query->get(static::URL_PARAM_EVENT);
        $this->getFacade()->triggerEvent($eventName, $stateMachineItemTransfer);

        return $this->redirectResponse(htmlentities($redirect));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return bool
     */
    protected function isValidTriggerEventPostRequest(Request $request): bool
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new BadRequestHttpException();
        }

        $form = $this->getFactory()
            ->createEventTriggerForm()
            ->handleRequest($request);

        return $form->isSubmitted() && $form->isValid();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return bool
     */
    protected function isValidTriggerEventItemPostRequest(Request $request): bool
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new BadRequestHttpException();
        }

        $form = $this->getFactory()
            ->createEventItemTriggerForm()
            ->handleRequest($request);

        return $form->isSubmitted() && $form->isValid();
    }

    /**
     * @param string $processName
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    protected function createStateMachineProcessTransfer(
        string $processName,
        string $stateMachineName
    ): StateMachineProcessTransfer {
        return (new StateMachineProcessTransfer())
            ->setProcessName($processName)
            ->setStateMachineName($stateMachineName);
    }

    /**
     * @param int $identifier
     * @param int $idState
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createStateMachineItemTransfer(int $identifier, int $idState): StateMachineItemTransfer
    {
        return (new StateMachineItemTransfer())
            ->setIdentifier($identifier)
            ->setIdItemState($idState);
    }
}
