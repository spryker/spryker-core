<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\Communication\OmsDependencyContainer;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method OmsFacade getFacade()
 * @method OmsQueryContainerInterface getQueryContainer()
 * @method OmsDependencyContainer getCommunicationFactory()
 */
class LogController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $transitionLogTable = $this->getCommunicationFactory()->createTransitionLogTable();

        return $this->viewResponse(['transitionLogTable' => $transitionLogTable->render()]);
    }

        /**
         * @param Request $request
         *
         * @return mixed
         */
    public function tableAjaxAction(Request $request)
    {
        $transitionLogTable = $this->getCommunicationFactory()->createTransitionLogTable();

        return $this->jsonResponse($transitionLogTable->fetchData());
    }

}
