<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Url\Communication\UrlDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method UrlDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @return JsonResponse
     */
    public function translationAction_original()
    {
        $grid = $this->getDependencyContainer()->getUrlKeyTranslationGrid();

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @return JsonResponse
     */
    public function translationAction()
    {
        $form = $this->getDependencyContainer()->getUrlForm();
        $form->init();

        return $this->jsonResponse($form->renderData());
    }

}
