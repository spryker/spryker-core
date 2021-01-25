<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class DeleteMerchantUserController extends AbstractCrudMerchantUserController
{
    public const PARAM_MERCHANT_USER_ID = 'merchant-user-id';

    protected const MESSAGE_INCORRECT_MERCHANT_USER_ID = 'Merchant user ID is incorrect.';

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

        $idMerchantUser = $this->castId($request->get(static::PARAM_MERCHANT_USER_ID));
        $merchantUserTransfer = $this->findMerchantUserTransfer($idMerchantUser);

        if (!$merchantUserTransfer) {
            $this->addErrorMessage(static::MESSAGE_INCORRECT_MERCHANT_USER_ID);

            return $this->redirectResponse($this->getMerchantListUrl());
        }

        $merchantUserListUrl = $this->getMerchantUserListUrl($merchantUserTransfer->getIdMerchant());
        $merchantUserResponseTransfer = $this->getFactory()
            ->getMerchantUserFacade()
            ->deleteMerchantUser($merchantUserTransfer);

        if (!$merchantUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage('Merchant user was not deleted.');

            return $this->redirectResponse($merchantUserListUrl);
        }

        $this->addSuccessMessage('Merchant user was deleted successfully.');

        return $this->redirectResponse($merchantUserListUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idMerchantUser = $this->castId($request->get(static::PARAM_MERCHANT_USER_ID));
        $merchantUserTransfer = $this->findMerchantUserTransfer($idMerchantUser);

        if (!$merchantUserTransfer) {
            $this->addErrorMessage(static::MESSAGE_INCORRECT_MERCHANT_USER_ID);

            return $this->redirectResponse($this->getMerchantListUrl());
        }

        $merchantUserListUrl = $this->getMerchantUserListUrl($merchantUserTransfer->getIdMerchant());
        $merchantUserDeleteConfirmForm = $this->getFactory()->getMerchantUserDeleteConfirmForm();

        return $this->viewResponse([
            'merchantUserDeleteConfirmForm' => $merchantUserDeleteConfirmForm->createView(),
            'merchantUser' => $merchantUserTransfer,
            'backUrl' => $merchantUserListUrl,
        ]);
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
            ->findMerchantUser($merchantUserCriteriaTransfer);
    }
}
