<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Auth\Communication\AuthDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AuthDependencyContainer getCommunicationFactory()
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
        $form = $this->getCommunicationFactory()->createLoginForm($request);

        return $this->viewResponse([
            'form' => json_encode($form->toArray()),
        ]);
    }

}
