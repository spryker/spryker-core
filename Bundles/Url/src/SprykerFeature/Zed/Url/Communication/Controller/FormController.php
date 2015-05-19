<?php

namespace SprykerFeature\Zed\Url\Communication\Controller;

use Generated\Shared\Transfer\UrlUrlTransfer;
use SprykerFeature\Zed\Url\Communication\UrlDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UrlDependencyContainer getDependencyContainer()
 */
class FormController extends AbstractController
{
    /**
     * @todo finish in next PR
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getUrlForm($request);
        $form->init();

        if ($form->isValid()) {
            $facade = $this->getLocator()->url()->facade();

            $url = new UrlUrlTransfer();
            $url->fromArray($form->getRequestData());
            $url->setResourceType('product');

            $facade->saveUrl($url);
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @deprecated this is for test purpose
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function demoAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getDemoForm($request);
        $form->init();

        return $this->jsonResponse($form->renderData());
    }
}