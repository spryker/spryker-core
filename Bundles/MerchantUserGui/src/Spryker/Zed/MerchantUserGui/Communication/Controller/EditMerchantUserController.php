<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class EditMerchantUserController extends AbstractController
{
    public const MERCHANT_USER_ID_PARAM_NAME = 'merchant-user-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchantUser = $this->castId($request->get(static::MERCHANT_USER_ID_PARAM_NAME));

        $dataProvider = $this->getFactory()->createMerchantUserUpdateFormDataProvider();
        $merchantUserTransfer = $dataProvider->getData($idMerchantUser);

        $merchantUserUpdateForm = $this->getFactory()
            ->getMerchantUserUpdateForm($merchantUserTransfer->getUser(), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($merchantUserUpdateForm->isSubmitted() && $merchantUserUpdateForm->isValid()) {
            return $this->updateMerchant($merchantUserTransfer, $merchantUserUpdateForm);
        }

        return $this->viewResponse([
            'merchantUserForm' => $merchantUserUpdateForm->createView(),
            'idMerchant' => $merchantUserTransfer->getIdMerchant(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Symfony\Component\Form\FormInterface $merchantUserUpdateForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function updateMerchant(MerchantUserTransfer $merchantUserTransfer, FormInterface $merchantUserUpdateForm)
    {
        $redirectUrl = sprintf(
            '/merchant-gui/edit-merchant?id-merchant=%s%s',
            $merchantUserTransfer->getIdMerchant(),
            '#tab-content-merchant-user'
        );

        $userTransfer = $merchantUserUpdateForm->getData();
        $merchantUserTransfer->setUser($userTransfer);

        $merchantUserResponseTransfer = $this->getFactory()
            ->getMerchantUserFacade()
            ->update($merchantUserTransfer);

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
