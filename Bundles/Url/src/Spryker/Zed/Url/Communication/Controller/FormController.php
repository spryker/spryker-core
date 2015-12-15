<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Communication\Controller;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Communication\UrlDependencyContainer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method UrlDependencyContainer getDependencyContainer()
 */
class FormController extends AbstractController
{

    /**
     * @todo finish in next PR
     *
     * @return JsonResponse
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()->getUrlForm();
        $form->init();

        if ($form->isValid()) {
            $facade = $this->getFacade();

            $url = new UrlTransfer();
            $url->fromArray($form->getRequestData());
            $url->setResourceType('product');

            $facade->saveUrl($url);
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @deprecated this is for test purpose
     *
     * @return JsonResponse
     */
    public function demoAction()
    {
        $form = $this->getDependencyContainer()->getDemoForm();
        $form->init();

        return $this->jsonResponse($form->renderData());
    }

}
