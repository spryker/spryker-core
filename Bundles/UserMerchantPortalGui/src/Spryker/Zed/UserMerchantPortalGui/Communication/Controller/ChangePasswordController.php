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
    protected const URL_CHANGE_PASSWORD = '/user-merchant-portal-gui/change-password';

    protected const MESSAGE_CHANGE_PASSWORD_SUCCESS = 'Success! The Password is updated.';

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
                ]
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
                    $formData[ChangePasswordForm::FIELD_NEW_PASSWORD]
                );

            $responseData['postActions'] = [[
                'type' => 'close_overlay',
            ]];
            $responseData['notifications'] = [[
                'type' => 'success',
                'message' => $this->getFactory()
                    ->getTranslatorFacade()
                    ->trans(static::MESSAGE_CHANGE_PASSWORD_SUCCESS),
            ]];
        }

        return new JsonResponse($responseData);
    }
}
