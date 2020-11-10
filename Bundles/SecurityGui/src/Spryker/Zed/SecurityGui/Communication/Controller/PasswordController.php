<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SecurityGui\Communication\Form\ResetPasswordForm;
use Spryker\Zed\SecurityGui\Communication\Form\ResetPasswordRequestForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface getFacade()
 */
class PasswordController extends AbstractController
{
    public const PARAM_TOKEN = 'token';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function resetRequestAction(Request $request)
    {
        $resetRequestForm = $this->getFactory()->createResetPasswordRequestForm();
        $resetRequestForm->handleRequest($request);

        if ($resetRequestForm->isSubmitted() && $resetRequestForm->isValid()) {
            $formData = $resetRequestForm->getData();
            $this->getFactory()
                ->getUserPasswordResetFacade()
                ->requestPasswordReset($formData[ResetPasswordRequestForm::FIELD_EMAIL]);

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
        if (!$token) {
            $this->addErrorMessage('Request token is missing!');

            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlLogin()
            );
        }

        $isValidToken = $this->getFactory()
            ->getUserPasswordResetFacade()
            ->isValidPasswordResetToken($token);

        if (!$isValidToken) {
            $this->addErrorMessage('Invalid request token!');

            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlLogin()
            );
        }

        $resetPasswordForm = $this->getFactory()
            ->createResetPasswordForm()
            ->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $isPasswordReset = $this->getFactory()
                ->getUserPasswordResetFacade()
                ->resetPassword(
                    $token,
                    $formData[ResetPasswordForm::FIELD_PASSWORD]
                );

            if ($isPasswordReset) {
                $this->addSuccessMessage('Password updated.');
            } else {
                $this->addErrorMessage('Could not update password.');
            }

            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlLogin()
            );
        }

        return $this->viewResponse([
            'form' => $resetPasswordForm->createView(),
        ]);
    }
}
