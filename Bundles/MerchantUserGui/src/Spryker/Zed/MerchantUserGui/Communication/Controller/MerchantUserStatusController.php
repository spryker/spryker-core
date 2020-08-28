<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantUserStatusController extends AbstractCrudMerchantUserController
{
    public const PARAM_MERCHANT_USER_ID = 'merchant-user-id';

    protected const MESSAGE_ERROR_MERCHANT_WRONG_PARAMETERS = 'User status can\'t be updated.';
    protected const MESSAGE_SUCCESS_MERCHANT_STATUS_UPDATE = 'User status has been updated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idMerchantUser = $this->castId($request->query->get(static::PARAM_MERCHANT_USER_ID));
        $newMerchantUserStatus = $request->query->get('status');

        if (!$idMerchantUser || !$newMerchantUserStatus) {
            return $this->getWrongParametersErrorRedirect($this->getMerchantListUrl());
        }

        $merchantUserCriteriaTransfer = new MerchantUserCriteriaTransfer();
        $merchantUserCriteriaTransfer->setIdMerchantUser($idMerchantUser)->setWithUser(true);
        $merchantUserTransfer = $this->getFactory()
            ->getMerchantUserFacade()
            ->findMerchantUser($merchantUserCriteriaTransfer);

        if (!$merchantUserTransfer || !$merchantUserTransfer->getUser()) {
            return $this->getWrongParametersErrorRedirect($this->getMerchantListUrl());
        }

        $merchantUserTransfer->getUser()->setStatus($newMerchantUserStatus);
        $merchantResponseTransfer = $this->getFactory()->getMerchantUserFacade()->updateMerchantUser($merchantUserTransfer);
        $merchantUserListUrl = $this->getMerchantUserListUrl($merchantUserTransfer->getIdMerchant());

        if (!$merchantResponseTransfer->getIsSuccessful()) {
            return $this->getWrongParametersErrorRedirect($merchantUserListUrl);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_MERCHANT_STATUS_UPDATE);

        return $this->redirectResponse($merchantUserListUrl);
    }

    /**
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getWrongParametersErrorRedirect(string $redirectUrl): RedirectResponse
    {
        $this->addErrorMessage(static::MESSAGE_ERROR_MERCHANT_WRONG_PARAMETERS);

        return $this->redirectResponse($redirectUrl);
    }
}
