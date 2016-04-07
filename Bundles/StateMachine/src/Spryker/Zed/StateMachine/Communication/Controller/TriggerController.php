<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Controller;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacade getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 */
class TriggerController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventAction(Request $request)
    {
        $identifier = $request->query->get('identifier');
        $idState = $request->query->get('id-state');

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier($identifier);
        $stateMachineItemTransfer->setIdItemState($idState);

        $stateMachineName = $request->query->get('state-machine-name');
        $eventName = $request->query->get('event');
        $this->getFacade()->triggerEvent($eventName, $stateMachineName, [$stateMachineItemTransfer]);

        $redirect = $request->query->get('redirect', '/state-machine/list');
        return $this->redirectResponse($redirect);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventForNewItemAction(Request $request)
    {
        $stateMachineName = $request->query->get('state-machine-name');
        $processName = $request->query->get('process-name');

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName($stateMachineName);

        $identifier = $request->query->get('identifier');
        $this->getFacade()->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $redirect = $request->query->get('redirect', '/state-machine/list');
        return $this->redirectResponse($redirect);
    }

}
