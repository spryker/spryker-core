<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class EditMerchantUserController extends AbstractCrudMerchantUserController
{
    public const PARAM_MERCHANT_USER_ID = 'merchant-user-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchantUser = $this->castId($request->get(static::PARAM_MERCHANT_USER_ID));

        $dataProvider = $this->getFactory()->createMerchantUserUpdateFormDataProvider();
        $merchantUserTransfer = $dataProvider->getData($idMerchantUser);

        if (!$merchantUserTransfer) {
            $this->addErrorMessage('Merchant user ID is incorrect.');

            return $this->redirectResponse($this->getMerchantListUrl());
        }

        $merchantUserUpdateForm = $this->getFactory()
            ->getMerchantUserUpdateForm($merchantUserTransfer->getUser(), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($merchantUserUpdateForm->isSubmitted() && $merchantUserUpdateForm->isValid()) {
            return $this->updateMerchantUser($merchantUserTransfer, $merchantUserUpdateForm);
        }

        return $this->viewResponse([
            'merchantUserForm' => $merchantUserUpdateForm->createView(),
            'idMerchant' => $merchantUserTransfer->getIdMerchant(),
            'backUrl' => $this->getMerchantUserListUrl($merchantUserTransfer->getIdMerchant()),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Symfony\Component\Form\FormInterface $merchantUserUpdateForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function updateMerchantUser(
        MerchantUserTransfer $merchantUserTransfer,
        FormInterface $merchantUserUpdateForm
    ): RedirectResponse {
        $redirectUrl = $this->getMerchantUserListUrl($merchantUserTransfer->getIdMerchant());
        $userTransfer = $merchantUserUpdateForm->getData();
        $merchantUserTransfer->setUser($userTransfer);

        $merchantUserResponseTransfer = $this->getFactory()
            ->getMerchantUserFacade()
            ->updateMerchantUser($merchantUserTransfer);

        if ($merchantUserResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage('Merchant user was successfully updated');

            return $this->redirectResponse($redirectUrl);
        }

        foreach ($merchantUserResponseTransfer->getErrors() as $merchantUserErrorTransfer) {
            $this->addErrorMessage($merchantUserErrorTransfer->getMessage());
        }

        return $this->redirectResponse($redirectUrl);
    }
}
