<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\ChangeEmailForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
 */
class ChangeEmailController extends AbstractController
{
    /**
     * @see \Spryker\Zed\UserMerchantPortalGui\Communication\Controller\MyAccountController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_MERCHANT_MY_ACCOUNT = '/user-merchant-portal-gui/my-account';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Success! The Email is updated.';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'Please resolve all errors.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_EMAIL_CHANGING_BLOCKED = 'Email changing has been blocked due to too many attempts.';

    /**
     * @var string
     */
    protected const SECURITY_BLOCKER_IDENTIFIER = 'email-change';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $isEmailChangingBlocked = $this->isEmailChangingBlocked($request);
        $changeEmailFormDataProvider = $this->getFactory()->createChangeEmailFormDataProvider();

        $changeEmailForm = $this->getFactory()
            ->createChangeEmailForm(
                $changeEmailFormDataProvider->getData(),
                $changeEmailFormDataProvider->getOptions(!$isEmailChangingBlocked),
            )->handleRequest($request);

        $responseData = [
            'form' => $this->renderView(
                '@UserMerchantPortalGui/Partials/change-email-form-overlay.twig',
                [
                    'changeEmailForm' => $changeEmailForm->createView(),
                ],
            )->getContent(),
        ];

        if (!$changeEmailForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        $userMerchantPortalGuiConfig = $this->getFactory()->getConfig();
        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        $changeEmailFormData = $changeEmailForm->getData();

        if (
            $userMerchantPortalGuiConfig->isSecurityBlockerForMerchantUserEmailChangingEnabled() &&
            $merchantUserTransfer->getUserOrFail()->getUsernameOrFail() !== $changeEmailFormData[ChangeEmailForm::FIELD_EMAIL]
        ) {
            $securityCheckAuthContextTransfer = (new SecurityCheckAuthContextTransfer())
                ->setIp($request->getClientIp())
                ->setAccount(static::SECURITY_BLOCKER_IDENTIFIER)
                ->setType($userMerchantPortalGuiConfig->getSecurityBlockerMerchantPortalUserEntityType());

            $this->getFactory()->getSecurityBlockerClient()->incrementLoginAttemptCount($securityCheckAuthContextTransfer);
        }

        if ($isEmailChangingBlocked) {
            $userMerchantPortalGuiFactory = $this->getFactory();

            $zedUiFormResponseTransfer = $userMerchantPortalGuiFactory
                ->getZedUiFactory()
                ->createZedUiFormResponseBuilder()
                ->addErrorNotification(
                    $userMerchantPortalGuiFactory
                        ->getTranslatorFacade()
                        ->trans(static::ERROR_MESSAGE_EMAIL_CHANGING_BLOCKED),
                )
                ->createResponse();

            $responseData = array_merge($responseData, $zedUiFormResponseTransfer->toArray());

            return new JsonResponse($responseData);
        }

        return $this->handleFormSubmission($changeEmailForm, $responseData);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $changeEmailForm
     * @param array<string, mixed> $responseData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function handleFormSubmission(FormInterface $changeEmailForm, array $responseData): JsonResponse
    {
        $userMerchantPortalGuiFactory = $this->getFactory();

        if (!$changeEmailForm->isValid()) {
            $zedUiFormResponseTransfer = $userMerchantPortalGuiFactory
                ->getZedUiFactory()
                ->createZedUiFormResponseBuilder()
                ->addErrorNotification(
                    $userMerchantPortalGuiFactory
                        ->getTranslatorFacade()
                        ->trans(static::RESPONSE_NOTIFICATION_MESSAGE_ERROR),
                )
                ->createResponse();

            $responseData = array_merge($responseData, $zedUiFormResponseTransfer->toArray());

            return new JsonResponse($responseData);
        }

        $formData = $changeEmailForm->getData();

        $merchantUserFacade = $userMerchantPortalGuiFactory->getMerchantUserFacade();
        $merchantUserTransfer = $merchantUserFacade->getCurrentMerchantUser();
        $merchantUserTransfer->getUserOrFail()->setUsername($formData[ChangeEmailForm::FIELD_EMAIL]);

        $userMerchantPortalGuiFactory
            ->createMerchantUserUpdater()
            ->updateMerchantUser($merchantUserTransfer);

        $merchantUserFacade->setCurrentMerchantUser($merchantUserTransfer);

        $zedUiFormResponseTransfer = $userMerchantPortalGuiFactory
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(
                $userMerchantPortalGuiFactory
                    ->getTranslatorFacade()
                    ->trans(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS),
            )
            ->addActionCloseDrawer()
            ->addActionRedirect(static::ROUTE_MERCHANT_MY_ACCOUNT)
            ->createResponse();

        $responseData = array_merge($responseData, $zedUiFormResponseTransfer->toArray());

        return new JsonResponse($responseData);
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
}
