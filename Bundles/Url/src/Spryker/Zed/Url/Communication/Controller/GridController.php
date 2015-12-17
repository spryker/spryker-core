<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Url\Communication\UrlCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method UrlCommunicationFactory getFactory()
 */
class GridController extends AbstractController
{

    /**
     * @return JsonResponse
     */
    public function translationAction_original()
    {
        $grid = $this->getFactory()->getUrlKeyTranslationGrid();

        return $this->jsonResponse($grid->renderData());
    }

    /**
     * @return JsonResponse
     */
    public function translationAction()
    {
        $form = $this->getFactory()->getUrlForm();
        $form->init();

        return $this->jsonResponse($form->renderData());
    }

}
