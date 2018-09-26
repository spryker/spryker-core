<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 */
class ListController extends AbstractController
{
    public const URL_PARAM_STATE_MACHINE = 'state-machine';

    /**
     * @return array
     */
    public function indexAction()
    {
        $stateMachines = [];
        foreach ($this->getFactory()->getStateMachineHandlerPlugins() as $stateMachineHandlerPlugin) {
            $stateMachines[] = $stateMachineHandlerPlugin->getStateMachineName();
        }

        return $this->viewResponse([
            'stateMachines' => $stateMachines,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function processAction(Request $request)
    {
        $stateMachineName = $request->query->get(self::URL_PARAM_STATE_MACHINE);

        return $this->viewResponse([
            'processes' => $this->getFacade()->getProcesses($stateMachineName),
            'stateMachineName' => $stateMachineName,
        ]);
    }
}
