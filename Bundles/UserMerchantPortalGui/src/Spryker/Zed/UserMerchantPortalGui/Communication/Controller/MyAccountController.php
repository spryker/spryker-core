<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\MerchantAccountForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
 */
class MyAccountController extends AbstractController
{
    /**
     * @var string
     */
    protected const ROUTE_MERCHANT_MY_ACCOUNT = '/user-merchant-portal-gui/my-account';

    /**
     * @see \Spryker\Zed\UserMerchantPortalGui\Communication\Controller\ChangePasswordController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_CHANGE_PASSWORD = '/user-merchant-portal-gui/change-password';

    /**
     * @see \Spryker\Zed\UserMerchantPortalGui\Communication\Controller\ChangeEmailController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_CHANGE_EMAIL = '/user-merchant-portal-gui/change-email';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_USER_UPDATE_SUCCESS = 'Success! The Account is updated.';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_USER_UPDATE_ERROR = 'Merchant user entity was not updated.';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_USER_VALIDATION_ERROR = 'Please resolve all errors.';

    /**
     * @var string
     */
    protected const INFO_MESSAGE_EMAIL_CHANGING_BLOCKED = 'Email changing has been blocked due to too many attempts.';

    /**
     * @var string
     */
    protected const SECURITY_BLOCKER_IDENTIFIER = 'email-change';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $isEmailChangingBlocked = $this->isEmailChangingBlocked($request);
        $merchantAccountFormDataProvider = $this->getFactory()
            ->createMerchantAccountFormDataProvider();

        $merchantAccountForm = $this->getFactory()
            ->createMerchantAccountForm(
                $merchantAccountFormDataProvider->getData(),
                $merchantAccountFormDataProvider->getOptions(!$isEmailChangingBlocked),
            )
            ->handleRequest($request);

        $userMerchantPortalGuiConfig = $this->getFactory()->getConfig();

        $response = [
            'merchantAccountForm' => $merchantAccountForm->createView(),
            'urlChangePassword' => static::ROUTE_CHANGE_PASSWORD,
            'urlChangeEmail' => static::ROUTE_CHANGE_EMAIL,
            'isEmailUpdatePasswordVerificationEnabled' => $userMerchantPortalGuiConfig->isEmailUpdatePasswordVerificationEnabled(),
        ];

        if (!$merchantAccountForm->isSubmitted()) {
            return $this->viewResponse($response);
        }

        if ($isEmailChangingBlocked) {
            $this->addInfoMessage(static::INFO_MESSAGE_EMAIL_CHANGING_BLOCKED);
        }

        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();

        if (
            $userMerchantPortalGuiConfig->isSecurityBlockerForMerchantUserEmailChangingEnabled() &&
            $merchantUserTransfer->getUserOrFail()->getUsernameOrFail() !== $merchantAccountForm->getData()[MerchantAccountForm::FIELD_USERNAME]
        ) {
            $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
                ->setIp($request->getClientIp())
                ->setAccount(static::SECURITY_BLOCKER_IDENTIFIER)
                ->setType($this->getFactory()->getConfig()->getSecurityBlockerMerchantPortalUserEntityType());

            $this->getFactory()->getSecurityBlockerClient()->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
        }

        if (!$merchantAccountForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_USER_VALIDATION_ERROR);

            return $this->viewResponse($response);
        }

        $this->handleFormSubmission($merchantAccountForm, $merchantUserTransfer, $isEmailChangingBlocked);

        return new RedirectResponse(static::ROUTE_MERCHANT_MY_ACCOUNT);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isEmailChangingBlocked(Request $request): bool
    {
        $userMerchantPortalGuiConfig = $this->getFactory()->getConfig();

        if (!$userMerchantPortalGuiConfig->isSecurityBlockerForMerchantUserEmailChangingEnabled()) {
            return false;
        }

        $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
            ->setIp($request->getClientIp())
            ->setAccount(static::SECURITY_BLOCKER_IDENTIFIER)
            ->setType($userMerchantPortalGuiConfig->getSecurityBlockerMerchantPortalUserEntityType());

        return $this
            ->getFactory()
            ->getSecurityBlockerClient()
            ->isAccountBlocked($securityCheckAuthContextTransfer)
            ->getIsBlockedOrFail();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantAccountForm
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param bool $isEmailChangingBlocked
     *
     * @return void
     */
    protected function handleFormSubmission(
        FormInterface $merchantAccountForm,
        MerchantUserTransfer $merchantUserTransfer,
        bool $isEmailChangingBlocked
    ): void {
        $merchantUserFacade = $this->getFactory()->getMerchantUserFacade();
        $merchantAccountFormData = $merchantAccountForm->getData();

        if ($isEmailChangingBlocked) {
            unset($merchantAccountFormData[MerchantAccountForm::FIELD_USERNAME]);
        }

        $merchantUserTransfer->getUserOrFail()
            ->fromArray($merchantAccountFormData, true);

        $merchantUserResponseTransfer = $merchantUserFacade
            ->updateMerchantUser($merchantUserTransfer);

        if (!$merchantUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_USER_UPDATE_ERROR);

            return;
        }

        $merchantUserTransfer = $this->switchLocaleIfChanged($merchantAccountForm, $merchantUserTransfer);
        $merchantUserFacade->setCurrentMerchantUser($merchantUserTransfer);

        $this->getFactory()
            ->createMerchantUserUpdater()
            ->updateMerchantUser($merchantUserTransfer);

        $this->addSuccessMessage(static::MESSAGE_MERCHANT_USER_UPDATE_SUCCESS);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantAccountForm
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function switchLocaleIfChanged(
        FormInterface $merchantAccountForm,
        MerchantUserTransfer $merchantUserTransfer
    ): MerchantUserTransfer {
        if ($this->getIsFkLocaleChanged($merchantAccountForm)) {
            $localeTransfer = $this->getFactory()
                ->getLocaleFacade()
                ->getLocaleById(
                    $merchantUserTransfer->getUserOrFail()->getFkLocaleOrFail(),
                );

            $merchantUserTransfer->getUserOrFail()
                ->setFkLocale($localeTransfer->getIdLocale())
                ->setLocaleName($localeTransfer->getLocaleName());
        }

        return $merchantUserTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantAccountForm
     *
     * @return bool
     */
    protected function getIsFkLocaleChanged(FormInterface $merchantAccountForm): bool
    {
        $defaultData = $merchantAccountForm->getConfig()->getData();
        $submittedData = $merchantAccountForm->getData();

        return $defaultData[MerchantAccountForm::FIELD_FK_LOCALE] !== $submittedData[MerchantAccountForm::FIELD_FK_LOCALE];
    }
}
