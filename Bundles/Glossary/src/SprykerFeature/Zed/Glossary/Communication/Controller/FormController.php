<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class FormController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function translationAction(Request $request)
    {
        $form = $this->getDependencyContainer()
            ->createKeyForm($request)
        ;
        $form->init();

        if ($form->isValid()) {

            $formData = $form->getRequestData();

            $facade = $this->getDependencyContainer()->createGlossaryFacade();
            $facade->saveGlossaryKeyTranslations($formData);
        }

        return $this->jsonResponse($form->renderData());
    }

}
