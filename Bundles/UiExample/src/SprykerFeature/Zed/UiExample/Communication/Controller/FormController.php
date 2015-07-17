<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UiExample\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\UiExample\Communication\UiExampleDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UiExampleDependencyContainer getDependencyContainer()
 */
class FormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uiExampleAction(Request $request)
    {
        $form = $this->getDependencyContainer()->getUiExampleForm($request);

        $form->init();

        if ($form->isValid()) {
            $form->getSubFormByName('vehicle_specs')->getForm()->getStateContainer()->clearResponseData();

            $form->getStateContainer()->clearResponseData();
        }

        return $this->jsonResponse($form->renderData());
    }

}
