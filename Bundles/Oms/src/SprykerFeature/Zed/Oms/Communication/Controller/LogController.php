<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Oms\Communication\OmsDependencyContainer;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method OmsFacade getFacade()
 * @method OmsQueryContainerInterface getQueryContainer()
 * @method OmsDependencyContainer getDependencyContainer()
 */
class LogController extends AbstractController
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $transitionLogTable = $this->getDependencyContainer()->createTransitionLogTable();
        return $this->viewResponse(['transitionLogTable' => $transitionLogTable->render()]);
    }

        /**
         * @param Request $request
         * @return mixed
         */
    public function tableAjaxAction(Request $request)
    {
        $transitionLogTable = $this->getDependencyContainer()->createTransitionLogTable();
        return $this->jsonResponse($transitionLogTable->fetchData());
    }

}
