<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use SprykerFeature\Zed\Url\Communication\UrlDependencyContainer;

/**
 * @method UrlDependencyContainer getDependencyContainer
 */
class IndexController extends AbstractController
{

    /**
     * indexAction
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
        $grid = $this->getDependencyContainer()->getUrlKeyTranslationGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

}
