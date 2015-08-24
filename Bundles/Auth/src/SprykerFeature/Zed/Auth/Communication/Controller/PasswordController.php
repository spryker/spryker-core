<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use SprykerFeature\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use SprykerFeature\Zed\Auth\Persistence\AuthQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Auth\Communication\Form\ResetPasswordForm;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 * @method AuthFacade getFacade()
 * @method AuthQueryContainer getQueryContainer()
 */
class PasswordController extends AbstractController
{
    const RESET_REDIRECT_URL = '/auth/login';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function resetRequestAction(Request $request)
    {
        $resetRequestForm = $this->getDependencyContainer()->createResetPasswordRequestForm();
        $resetRequestForm->handleRequest();

        if ($request->isMethod(Request::METHOD_POST) && $resetRequestForm->isValid()) {
            $formData = $resetRequestForm->getData();
            $this->getFacade()->requestPasswordReset($formData[ResetPasswordRequestForm::EMAIL]);
            $this->addSuccessMessage('Email sent. Please check your inbox for further instructions.');
        }

        return $this->viewResponse([
            'form' => $resetRequestForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function resetAction(Request $request)
    {
        if (empty($request->get('token'))) {
            $this->addErrorMessage('Request token is missing!');
            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        $isValidToken = $this->getFacade()->isValidPasswordResetToken($request->get('token'));

        if (empty($isValidToken)) {
            $this->addErrorMessage('Could not reset password!');
            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        $resetPasswordForm = $this->getDependencyContainer()->createResetPasswordForm();
        $resetPasswordForm->handleRequest();

        if ($request->isMethod(Request::METHOD_POST) && $resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $resetStatus = $this->getFacade()
                ->resetPassword(
                    $request->get('token'),
                    $formData[ResetPasswordForm::PASSWORD]
                );

            if (true === $resetStatus) {
                $this->addSuccessMessage('Password updated.');
            } else {
                $this->addErrorMessage('Could not update password.');
            }

            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        return $this->viewResponse([
            'form' => $resetPasswordForm->createView(),
        ]);
    }

}
