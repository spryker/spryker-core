<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 */
class FormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loginForm(Request $request)
    {
        $form = $this->getDependencyContainer()->createLoginForm($request);

        return $this->viewResponse([
            'form' => json_encode($form->toArray()),
        ]);
    }

}
