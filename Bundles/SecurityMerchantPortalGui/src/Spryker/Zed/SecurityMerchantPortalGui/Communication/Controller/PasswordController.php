<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
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
    /**
     * @var string
     */
    protected const PARAM_TOKEN = 'token';

    /**
     * @var string
     */
    protected const MESSAGE_USER_REQUEST_PASSWORD_SUCCESS = 'If there is an account associated with this email, you will receive an Email with further instructions.';

    /**
     * @uses \Spryker\Zed\MerchantUser\Business\Updater\MerchantUserUpdater::RESET_RASSWORD_PATH
     *
     * @var string
     */
    protected const RESET_PASSWORD_PATH = '/security-merchant-portal-gui/password/reset';

    /**
     * @var string
     */
    protected const MESSAGE_USER_PASSWORD_UPDATE_SUCCESS = 'Success! The password is updated.';

    /**
     * @var string
     */
    protected const MESSAGE_USER_PASSWORD_UPDATE_ERROR = 'Could not update password.';

    /**
     * @var string
     */
    protected const MESSAGE_MISSING_TOKEN_ERROR = 'Request token is missing!';

    /**
     * @var string
     */
    protected const MESSAGE_INVALID_TOKEN_ERROR = 'Invalid request token!';

    /**
     * @var string
     */
    protected const BLOCKER_IDENTIFIER = 'password-reset';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function incrementPasswordResetBlocker(Request $request): void
    {
        $config = $this->getFactory()->getConfig();
        if (!$config->isMerchantPortalSecurityBlockerEnabled()) {
            return;
        }

        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setIp($request->getClientIp())
            ->setAccount(static::BLOCKER_IDENTIFIER)
            ->setType($config->getMerchantPortalSecurityBlockerEntityType());

        $this
            ->getFactory()
            ->getSecurityBlockerClient()
            ->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isPasswordResetBlocked(Request $request): bool
    {
        $config = $this->getFactory()->getConfig();
        if (!$config->isMerchantPortalSecurityBlockerEnabled()) {
            return false;
        }

        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setIp($request->getClientIp())
            ->setAccount(static::BLOCKER_IDENTIFIER)
            ->setType($config->getMerchantPortalSecurityBlockerEntityType());

        return (bool)$this
            ->getFactory()
            ->getSecurityBlockerClient()
            ->isAccountBlocked($securityCheckAuthContextTransfer)
            ->getIsBlocked();
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

        if ($this->isPasswordResetBlocked($request)) {
            return [
                'form' => $resetRequestForm->createView(),
            ];
        }

        if ($resetRequestForm->isSubmitted()) {
            $this->incrementPasswordResetBlocker($request);
        }

        if ($resetRequestForm->isSubmitted() && $resetRequestForm->isValid()) {
            $formData = $resetRequestForm->getData();

            $merchantUser = $this->getFactory()
                ->getMerchantUserFacade()
                ->findMerchantUser(
                    (new MerchantUserCriteriaTransfer())
                        ->setUsername($formData[MerchantResetPasswordRequestForm::FIELD_EMAIL]),
                );

            if ($merchantUser) {
                $this->getFactory()
                    ->getMerchantUserFacade()
                    ->requestPasswordReset(
                        (new UserPasswordResetRequestTransfer())
                            ->setEmail($formData[MerchantResetPasswordRequestForm::FIELD_EMAIL])
                            ->setResetPasswordPath(static::RESET_PASSWORD_PATH),
                    );
            }

            $this->addSuccessMessage(
                static::MESSAGE_USER_REQUEST_PASSWORD_SUCCESS,
            );

            $this->getFactory()->createAuditLogger()->addPasswordResetRequestedAuditLog();

            return $this->viewResponse([]);
        }

        return $this->viewResponse([
            'form' => $resetRequestForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function resetAction(Request $request)
    {
        /** @var string $token */
        $token = $request->query->get(static::PARAM_TOKEN);

        if (!$this->isValidToken($token)) {
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
                ->getMerchantUserFacade()
                ->setNewPassword(
                    $token,
                    $formData[MerchantResetPasswordForm::FIELD_PASSWORD],
                );

            if ($isPasswordReset) {
                $this->addSuccessMessage(static::MESSAGE_USER_PASSWORD_UPDATE_SUCCESS);

                $this->getFactory()->createAuditLogger()->addPasswordUpdatedAfterResetAuditLog();
            } else {
                $this->addErrorMessage(static::MESSAGE_USER_PASSWORD_UPDATE_ERROR);
            }

            return $this->redirectResponse(
                $this->getFactory()->getConfig()->getUrlLogin(),
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
            $this->addErrorMessage(static::MESSAGE_MISSING_TOKEN_ERROR);

            return false;
        }
        $isValidToken = $this->getFactory()
            ->getMerchantUserFacade()
            ->isValidPasswordResetToken($token);

        if (!$isValidToken) {
            $this->addErrorMessage(static::MESSAGE_INVALID_TOKEN_ERROR);

            return false;
        }

        return true;
    }
}
