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
class PasswordController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function resetAction(Request $request)
    {
        $facade = $this->getDependencyContainer()->locateAuthFacade();

        $form = $this->getDependencyContainer()->createResetPasswordForm($request);
        $form->init();

        $formData = $form->getRequestData();

        if ($form->isValid()) {
//            if ($facade->resetPassword($formData['username'])) {
                return $this->jsonResponse([], 200, [
                    'Spy-Location' => '/',
                ]);
//            }
        }

        return $this->jsonResponse($form->toArray(), 400);
    }

}
