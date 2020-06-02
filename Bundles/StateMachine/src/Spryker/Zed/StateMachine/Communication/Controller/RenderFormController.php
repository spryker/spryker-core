<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineRepositoryInterface getRepository()
 */
class RenderFormController extends AbstractController
{
    public const URL_PARAM_IDENTIFIER = 'identifier';
    public const URL_PARAM_ID_STATE = 'id-state';
    public const URL_PARAM_ID_PROCESS = 'id-process';
    public const URL_PARAM_STATE_MACHINE_NAME = 'state-machine-name';
    public const URL_PARAM_PROCESS_NAME = 'process-name';
    public const URL_PARAM_REDIRECT = 'redirect';
    public const URL_PARAM_EVENTS = 'events';
    public const URL_PARAM_EVENT_NAME = 'event-name';

    public const DEFAULT_REDIRECT_URL = '/state-machine/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function eventAction(Request $request): array
    {
        $identifier = $this->castId($request->attributes->getInt(static::URL_PARAM_IDENTIFIER));
        $idState = $this->castId($request->attributes->getInt(static::URL_PARAM_ID_STATE));
        $redirect = $request->attributes->get(static::URL_PARAM_REDIRECT, static::DEFAULT_REDIRECT_URL);
        $events = $request->attributes->get(static::URL_PARAM_EVENTS);

        $eventTriggerFormDataProvider = $this->getFactory()->createEventTriggerFormDataProvider();

        $eventTriggerFormCollection = [];
        foreach ($events as $event) {
            $eventTriggerFormCollection[$event] = $this->getFactory()
                ->createEventTriggerForm($eventTriggerFormDataProvider->getOptions($identifier, $redirect, $idState, $event))
                ->createView();
        }

        return $this->viewResponse([
            'formCollection' => $eventTriggerFormCollection,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function eventItemAction(Request $request): array
    {
        $identifier = $this->castId($request->attributes->getInt(static::URL_PARAM_IDENTIFIER));
        $redirect = $request->attributes->get(static::URL_PARAM_REDIRECT, static::DEFAULT_REDIRECT_URL);
        $stateMachineName = $request->attributes->get(static::URL_PARAM_STATE_MACHINE_NAME);
        $processName = $request->attributes->get(static::URL_PARAM_PROCESS_NAME);
        $eventName = $request->attributes->get(static::URL_PARAM_EVENT_NAME);

        $eventItemTriggerDataProvider = $this->getFactory()->createEventItemTriggerFormDataProvider();
        $form = $this->getFactory()
            ->createEventItemTriggerForm(
                $eventItemTriggerDataProvider->getOptions($identifier, $redirect, $eventName, $stateMachineName, $processName)
            );

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
