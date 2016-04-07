<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacade getFacade()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 */
class ListController extends AbstractController
{
    /**
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->viewResponse([
            'stateMachines' => [
                'Test'
            ],
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function processAction(Request $request)
    {
        $stateMachineName = $request->query->get('state-machine');

        return $this->viewResponse([
            'processes' => $this->getFacade()->getProcesses($stateMachineName),
            'stateMachineName' => $stateMachineName,
        ]);
    }

}
