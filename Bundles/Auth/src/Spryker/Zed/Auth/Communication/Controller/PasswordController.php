<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Controller;

use Spryker\Zed\Auth\Communication\Form\ResetPasswordForm;
use Spryker\Zed\Auth\Communication\Form\ResetPasswordRequestForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacadeInterface getFacade()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
 */
class PasswordController extends AbstractController
{
    public const PARAM_TOKEN = 'token';
    public const RESET_REDIRECT_URL = '/auth/login';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function resetRequestAction(Request $request)
    {
        $resetRequestForm = $this->getFactory()->createResetPasswordRequestForm();
        $resetRequestForm->handleRequest($request);

        if ($resetRequestForm->isSubmitted() && $resetRequestForm->isValid()) {
            $formData = $resetRequestForm->getData();
            $this->getFacade()->requestPasswordReset($formData[ResetPasswordRequestForm::FIELD_EMAIL]);
            $this->addSuccessMessage(
                'If there is an account associated with this email, you will receive an Email with further instructions.'
            );
        }

        return $this->viewResponse([
            'form' => $resetRequestForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resetAction(Request $request)
    {
        $token = $request->query->get(self::PARAM_TOKEN);
        if (empty($token)) {
            $this->addErrorMessage('Request token is missing!');

            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        $isValidToken = $this->getFacade()->isValidPasswordResetToken($token);

        if (empty($isValidToken)) {
            $this->addErrorMessage('Invalid request token!');

            return $this->redirectResponse(self::RESET_REDIRECT_URL);
        }

        $resetPasswordForm = $this->getFactory()
            ->createResetPasswordForm()
            ->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $resetStatus = $this->getFacade()
                ->resetPassword(
                    $token,
                    $formData[ResetPasswordForm::FIELD_PASSWORD]
                );

            if ($resetStatus === true) {
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
