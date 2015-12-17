<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Auth\Communication\AuthCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AuthCommunicationFactory getFactory()
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
        $form = $this->getFactory()->createLoginForm($request);

        return $this->viewResponse([
            'form' => json_encode($form->toArray()),
        ]);
    }

}
