<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class DeleteMerchantUserController extends AbstractController
{
    public const PARAM_ID_MERCHANT_USER = 'id-merchant-user';

    protected const MESSAGE_MERCHANT_USER_DELETE_ERROR = 'Merchant user was not deleted.';
    protected const MESSAGE_INCORRECT_MERCHANT_USER_ID = 'Merchant user ID is incorrect.';
    protected const URL_REDIRECT_MERCHANT_LIST = '/merchant-gui/list-merchant';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_DELETE)) {
            throw new MethodNotAllowedHttpException(
                [Request::METHOD_DELETE],
                'This action requires a DELETE request.'
            );
        }

        $idMerchantUser = $this->castId($request->get(static::PARAM_ID_MERCHANT_USER));
        $merchantUserTransfer = $this->findMerchantUserTransfer($idMerchantUser);

        if (!$merchantUserTransfer) {
            $this->addErrorMessage(static::MESSAGE_INCORRECT_MERCHANT_USER_ID);

            return $this->redirectResponse(static::URL_REDIRECT_MERCHANT_LIST);
        }

        $redirectUrl = $this->getRedirectUrl($merchantUserTransfer);

        if ($this->isCurrentUser($merchantUserTransfer->getIdUser())) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_USER_DELETE_ERROR);

            return $this->redirectResponse($redirectUrl);
        }

        $userTransfer = $this->getFactory()
            ->getUserFacade()
            ->removeUser($merchantUserTransfer->getIdUser());

        if ($userTransfer->getStatus() !== SpyUserTableMap::COL_STATUS_DELETED) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_USER_DELETE_ERROR);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addSuccessMessage('Merchant user was deleted successfully.');

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idMerchantUser = $this->castId($request->get(static::PARAM_ID_MERCHANT_USER));
        $merchantUserTransfer = $this->findMerchantUserTransfer($idMerchantUser);

        if (!$merchantUserTransfer) {
            $this->addErrorMessage(static::MESSAGE_INCORRECT_MERCHANT_USER_ID);

            return $this->redirectResponse(static::URL_REDIRECT_MERCHANT_LIST);
        }

        $redirectUrl = $this->getRedirectUrl($merchantUserTransfer);

        if ($this->isCurrentUser($merchantUserTransfer->getIdUser())) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_USER_DELETE_ERROR);

            return $this->redirectResponse($redirectUrl);
        }

        $merchantUserDeleteConfirmForm = $this->getFactory()->getMerchantUserDeleteConfirmForm();

        return $this->viewResponse([
            'merchantUserDeleteConfirmForm' => $merchantUserDeleteConfirmForm->createView(),
            'merchantUser' => $merchantUserTransfer,
            'backUrl' => $redirectUrl,
        ]);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    protected function isCurrentUser(int $idUser): bool
    {
        $currentUserTransfer = $this->getFactory()->getUserFacade()->getCurrentUser();

        return $currentUserTransfer->getIdUser() === $idUser;
    }

    /**
     * @param int $idMerchantUser
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    protected function findMerchantUserTransfer(int $idMerchantUser): ?MerchantUserTransfer
    {
        $merchantUserCriteriaTransfer = (new MerchantUserCriteriaTransfer())
            ->setIdMerchantUser($idMerchantUser)
            ->setWithUser(true);

        return $this->getFactory()
            ->getMerchantUserFacade()
            ->findOne($merchantUserCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return string
     */
    protected function getRedirectUrl(MerchantUserTransfer $merchantUserTransfer): string
    {
        return sprintf(
            '/merchant-gui/edit-merchant?id-merchant=%s%s',
            $merchantUserTransfer->getIdMerchant(),
            '#tab-content-merchant-user'
        );
    }
}
