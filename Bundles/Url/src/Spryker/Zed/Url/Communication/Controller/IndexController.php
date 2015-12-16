<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spryker\Zed\Url\Communication\UrlDependencyContainer;

/**
 * @method UrlDependencyContainer getCommunicationFactory
 */
class IndexController extends AbstractController
{

    /**
     * indexAction
     *
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function gridAction(Request $request)
    {
        $grid = $this->getCommunicationFactory()->getUrlKeyTranslationGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
