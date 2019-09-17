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

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 */
class TriggerController extends AbstractController
{
    /** @deprecated use PARAM_ID_STATE instead. */
    public const URL_PARAM_ID_STATE = 'id-state';

    /** @deprecated use PARAM_ID_PROCESS instead. */
    public const URL_PARAM_ID_PROCESS = 'id-process';

    /** @deprecated use PARAM_STATE_MACHINE_NAME instead. */
    public const URL_PARAM_STATE_MACHINE_NAME = 'state-machine-name';

    /** @deprecated use PARAM_PROCESS_NAME instead. */
    public const URL_PARAM_PROCESS_NAME = 'process-name';

    public const URL_PARAM_IDENTIFIER = 'identifier';
    public const URL_PARAM_REDIRECT = 'redirect';
    public const URL_PARAM_EVENT = 'event';
    public const PARAM_ID_STATE = 'idState';
    public const PARAM_ID_PROCESS = 'idProcess';
    public const PARAM_STATE_MACHINE_NAME = 'stateMachineName';
    public const PARAM_PROCESS_NAME = 'processName';

    public const DEFAULT_REDIRECT_URL = '/state-machine/list';

    protected const ERROR_INVALID_FORM = 'Form is invalid';

    /**
     * @deprecated use submitTriggerEventForNewItemAction instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventForNewItemAction(Request $request)
    {
        $stateMachineName = $request->query->get(self::URL_PARAM_STATE_MACHINE_NAME);
        $processName = $request->query->get(self::URL_PARAM_PROCESS_NAME);

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName($stateMachineName);

        $identifier = $this->castId($request->query->get(self::URL_PARAM_IDENTIFIER));
        $this->getFacade()->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $redirect = $request->query->get(self::URL_PARAM_REDIRECT, self::DEFAULT_REDIRECT_URL);

        return $this->redirectResponse(htmlentities($redirect));
    }

    /**
     * @deprecated use submitTriggerEventAction instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventAction(Request $request)
    {
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
    public function submitTriggerEventItemAction(Request $request)
    {
        $redirect = $request->get(static::URL_PARAM_REDIRECT, static::DEFAULT_REDIRECT_URL);

        $form = $this->getFactory()
            ->createStateMachineTriggerFormFactory()
            ->createEventItemTriggerForm()
            ->handleRequest($request);

        if (!($request->isMethod(Request::METHOD_POST) && $form->isSubmitted() && $form->isValid())) {
            $this->addErrorMessage(static::ERROR_INVALID_FORM);

            return $this->redirectResponse($redirect);
        }

        /** @var \Generated\Shared\Transfer\EventItemTriggerFormDataTransfer $data */
        $data = $form->getData();

        $stateMachineProcessTransfer = $this->createStateMachineProcessTransfer($data->getProcessName(), $data->getStateMachineName());

        $this->getFacade()->triggerForNewStateMachineItem($stateMachineProcessTransfer, $data->getIdentifier());

        return $this->redirectResponse(htmlentities($data->getRedirect()));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function submitTriggerEventAction(Request $request)
    {
        $redirect = $request->get(static::URL_PARAM_REDIRECT, static::DEFAULT_REDIRECT_URL);

        $form = $this->getFactory()
            ->createStateMachineTriggerFormFactory()
            ->createEventTriggerForm()
            ->handleRequest($request);

        if (!($request->isMethod(Request::METHOD_POST) && $form->isSubmitted() && $form->isValid())) {
            $this->addErrorMessage(static::ERROR_INVALID_FORM);

            return $this->redirectResponse($redirect);
        }

        /** @var \Generated\Shared\Transfer\EventTriggerFormDataTransfer $data */
        $data = $form->getData();

        $stateMachineItemTransfer = $this->createStateMachineItemTransfer($data->getIdentifier(), $data->getIdState());

        $this->getFacade()->triggerEvent($data->getEvent(), $stateMachineItemTransfer);

        return $this->redirectResponse(htmlentities($data->getRedirect()));
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
