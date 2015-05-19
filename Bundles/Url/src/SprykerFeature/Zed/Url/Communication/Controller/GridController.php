<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Url\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Url\Communication\UrlDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UrlDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function translationAction_original(Request $request)
    {
        $grid = $this->getDependencyContainer()->getUrlKeyTranslationGrid($request);

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function translationAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getUrlForm($request);
        $form->init();

        return $this->jsonResponse($form->renderData());
    }
}
