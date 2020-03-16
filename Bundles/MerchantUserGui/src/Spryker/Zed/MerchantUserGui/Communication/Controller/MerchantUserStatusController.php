<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantUserStatusController extends AbstractController
{
    protected const MESSAGE_ERROR_MERCHANT_WRONG_PARAMETERS = 'User status can\'t be updated.';
    protected const MESSAGE_SUCCESS_MERCHANT_STATUS_UPDATE = 'User status has been updated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idMerchantUser = $this->castId($request->query->get('merchant-user-id'));
        $newMerchantUserStatus = $request->query->get('status');
        $redirectUrl = sprintf('%s#%s', $request->headers->get('referer'), 'tab-content-merchant-user');

        if (!$idMerchantUser || !$newMerchantUserStatus) {
            return $this->wrongParametersErrorRedirect($redirectUrl);
        }

        $merchantUserCriteriaTransfer = new MerchantUserCriteriaTransfer();
        $merchantUserCriteriaTransfer->setIdMerchantUser($idMerchantUser)->setWithUser(true);
        $merchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->findOne($merchantUserCriteriaTransfer);
        if (!$merchantUserTransfer || !$merchantUserTransfer->getUser()) {
            return $this->wrongParametersErrorRedirect($redirectUrl);
        }

        $merchantUserTransfer->getUser()->setStatus($newMerchantUserStatus);

        $merchantResponseTransfer = $this->getFactory()->getMerchantUserFacade()->update($merchantUserTransfer);

        if (!$merchantResponseTransfer->getIsSuccessful()) {
            return $this->wrongParametersErrorRedirect($redirectUrl);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_MERCHANT_STATUS_UPDATE);

        return $this->redirectResponseExternal($redirectUrl);
    }

    /**
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function wrongParametersErrorRedirect(string $redirectUrl): RedirectResponse
    {
        $this->addErrorMessage(static::MESSAGE_ERROR_MERCHANT_WRONG_PARAMETERS);

        return $this->redirectResponseExternal($redirectUrl);
    }
}
