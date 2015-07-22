<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use SprykerFeature\Zed\Auth\Communication\Form\ResetPasswordForm;
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

        $form = $this->getDependencyContainer()
            ->createResetPasswordForm($request)
            ->init()
        ;
        $form->handleRequest();

        $message = $messageType = null;

        if ($request->isMethod(Request::METHOD_POST) && $form->isValid()) {
            // @todo implement resetPassword in AuthFacade
//            $formData = $form->getData();
//            $facade = $this->getDependencyContainer()->locateAuthFacade();
//            if ($facade->resetPassword($formData[ResetPasswordForm::EMAIL])) {
//                $message = 'Password sent on email';
//                $messageType = 'success';
//            } else {
                $message = 'User not found';
                $messageType = 'error';
//            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'message' => $message,
            'messageType' => $messageType,
        ]);
    }

}
