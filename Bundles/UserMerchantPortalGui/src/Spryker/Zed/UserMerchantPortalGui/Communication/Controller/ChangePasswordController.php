<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\ChangePasswordForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\UserMerchantPortalGui\Communication\UserMerchantPortalGuiCommunicationFactory getFactory()
 */
class ChangePasswordController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Success! The Password is updated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $changePasswordForm = $this->getFactory()
            ->createChangePasswordForm()
            ->handleRequest($request);

        $responseData = [
            'form' => $this->renderView(
                '@UserMerchantPortalGui/Partials/change-password-form-overlay.twig',
                [
                    'changePasswordForm' => $changePasswordForm->createView(),
                ],
            )->getContent(),
        ];

        if (!$changePasswordForm->isSubmitted()) {
            return new JsonResponse($responseData);
        }

        if ($changePasswordForm->isValid()) {
            $formData = $changePasswordForm->getData();

            $this->getFactory()
                ->createMerchantUserUpdater()
                ->updateCurrentMerchantUserPassword(
                    $formData[ChangePasswordForm::FIELD_NEW_PASSWORD],
                );

            $zedUiFormResponseTransfer = $this->getFactory()
                ->getZedUiFactory()
                ->createZedUiFormResponseBuilder()
                ->addSuccessNotification(
                    $this->getFactory()
                        ->getTranslatorFacade()
                        ->trans(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS),
                )
                ->addActionCloseDrawer()
                ->createResponse();

            $responseData = array_merge($responseData, $zedUiFormResponseTransfer->toArray());
        }

        return new JsonResponse($responseData);
    }
}
