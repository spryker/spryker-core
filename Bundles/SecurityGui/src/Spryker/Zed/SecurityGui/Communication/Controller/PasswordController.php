<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Controller;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
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
    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_PASSWORD_RESET_EMAIL_SENT_MESSAGE = 'If there is an account associated with this email, you will receive an Email with further instructions.';

    /**
     * @var string
     */
    public const PARAM_TOKEN = 'token';

    /**
     * @var string
     */
    protected const BLOCKER_IDENTIFIER = 'password-reset';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isPasswordResetBlocked(Request $request): bool
    {
        $config = $this->getFactory()->getConfig();
        if (!$config->isBackofficeUserSecurityBlockerEnabled()) {
            return false;
        }

        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setIp($request->getClientIp())
            ->setAccount(static::BLOCKER_IDENTIFIER)
            ->setType($config->getBackofficeUserSecurityBlockerEntityType());

        return (bool)$this
            ->getFactory()
            ->getSecurityBlockerClient()
            ->isAccountBlocked($securityCheckAuthContextTransfer)
            ->getIsBlockedOrFail();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function incrementPasswordResetBlocker(Request $request): void
    {
        $config = $this->getFactory()->getConfig();
        if (!$config->isBackofficeUserSecurityBlockerEnabled()) {
            return;
        }

        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setIp($request->getClientIp())
            ->setAccount(static::BLOCKER_IDENTIFIER)
            ->setType($config->getBackofficeUserSecurityBlockerEntityType());

        $this
            ->getFactory()
            ->getSecurityBlockerClient()
            ->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function resetRequestAction(Request $request): array
    {
        $resetRequestForm = $this->getFactory()->createResetPasswordRequestForm();
        $resetRequestForm->handleRequest($request);
        $responseViewData = [
            'form' => $resetRequestForm->createView(),
        ];

        if ($this->isPasswordResetBlocked($request)) {
            $this->addSuccessMessage(
                static::SUCCESS_MESSAGE_PASSWORD_RESET_EMAIL_SENT_MESSAGE,
            );

            return $this->viewResponse($responseViewData);
        }

        if ($resetRequestForm->isSubmitted()) {
            $this->incrementPasswordResetBlocker($request);
        }

        if ($resetRequestForm->isSubmitted() && $resetRequestForm->isValid()) {
            $formData = $resetRequestForm->getData();
            $this->getFactory()
                ->getUserPasswordResetFacade()
                ->requestPasswordReset(
                    (new UserPasswordResetRequestTransfer())
                        ->setEmail($formData[ResetPasswordRequestForm::FIELD_EMAIL]),
                );

            $this->addSuccessMessage(
                static::SUCCESS_MESSAGE_PASSWORD_RESET_EMAIL_SENT_MESSAGE,
            );
        }

        return $this->viewResponse($responseViewData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function resetAction(Request $request)
    {
        $token = (string)$request->query->get(static::PARAM_TOKEN);
        if (!$token) {
            $this->addErrorMessage('Request token is missing!');

            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlLogin(),
            );
        }

        $isValidToken = $this->getFactory()
            ->getUserPasswordResetFacade()
            ->isValidPasswordResetToken($token);

        if (!$isValidToken) {
            $this->addErrorMessage('Invalid request token!');

            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlLogin(),
            );
        }

        $resetPasswordForm = $this->getFactory()
            ->createResetPasswordForm()
            ->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $isPasswordReset = $this->getFactory()
                ->getUserPasswordResetFacade()
                ->setNewPassword(
                    $token,
                    $formData[ResetPasswordForm::FIELD_PASSWORD],
                );

            if ($isPasswordReset) {
                $this->addSuccessMessage('Password updated.');
            } else {
                $this->addErrorMessage('Could not update password.');
            }

            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlLogin(),
            );
        }

        return $this->viewResponse([
            'form' => $resetPasswordForm->createView(),
        ]);
    }
}
