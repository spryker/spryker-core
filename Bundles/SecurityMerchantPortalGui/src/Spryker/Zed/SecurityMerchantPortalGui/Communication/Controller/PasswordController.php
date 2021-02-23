<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantResetPasswordForm;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantResetPasswordRequestForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 */
class PasswordController extends AbstractController
{
    public const PARAM_TOKEN = 'token';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function resetRequestAction(Request $request): array
    {
        $resetRequestForm = $this->getFactory()->createResetPasswordRequestForm();
        $resetRequestForm->handleRequest($request);

        if ($resetRequestForm->isSubmitted() && $resetRequestForm->isValid()) {
            $formData = $resetRequestForm->getData();

            $merchantUser = $this->getFactory()
                ->getMerchantUserFacade()
                ->findMerchantUser(
                    (new MerchantUserCriteriaTransfer())
                        ->setUsername($formData[MerchantResetPasswordRequestForm::FIELD_EMAIL])
                );

            if ($merchantUser) {
                $this->getFactory()
                    ->getMerchantUserFacade()
                    ->requestPasswordReset(
                        (new UserPasswordResetRequestTransfer())
                            ->setEmail($formData[MerchantResetPasswordRequestForm::FIELD_EMAIL])
                            ->setResetPasswordPath('/security-merchant-portal-gui/password/reset')
                    );
            }

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
        /** @var string $token */
        $token = $request->query->get(self::PARAM_TOKEN);

        if (!$this->isValidToken($token)) {
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
                ->getMerchantUserFacade()
                ->setNewPassword(
                    $token,
                    $formData[MerchantResetPasswordForm::FIELD_PASSWORD]
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

    /**
     * @param string|null $token
     *
     * @return bool
     */
    protected function isValidToken(?string $token): bool
    {
        if (!$token) {
            $this->addErrorMessage('Request token is missing!');

            return false;
        }
        $isValidToken = $this->getFactory()
            ->getMerchantUserFacade()
            ->isValidPasswordResetToken($token);

        if (!$isValidToken) {
            $this->addErrorMessage('Invalid request token!');

            return false;
        }

        return true;
    }
}
