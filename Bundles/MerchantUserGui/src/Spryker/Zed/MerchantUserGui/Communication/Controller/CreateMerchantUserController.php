<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class CreateMerchantUserController extends AbstractCrudMerchantUserController
{
    protected const PARAM_MERCHANT_ID = 'merchant-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchant = $this->castId($request->get(static::PARAM_MERCHANT_ID));

        $merchantUserForm = $this->getFactory()
            ->getMerchantUserCreateForm(new UserTransfer())
            ->handleRequest($request);

        if ($merchantUserForm->isSubmitted() && $merchantUserForm->isValid()) {
            return $this->createMerchantUser($idMerchant, $merchantUserForm);
        }

        return $this->viewResponse([
            'merchantUserForm' => $merchantUserForm->createView(),
            'idMerchant' => $idMerchant,
            'backUrl' => $this->getMerchantUserListUrl($idMerchant),
        ]);
    }

    /**
     * @param int $idMerchant
     * @param \Symfony\Component\Form\FormInterface $merchantUserForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createMerchantUser(int $idMerchant, FormInterface $merchantUserForm)
    {
        $userTransfer = $merchantUserForm->getData();

        $merchantUserResponseTransfer = $this->getFactory()->getMerchantUserFacade()->createMerchantUser(
            (new MerchantUserTransfer())->setUser($userTransfer)->setIdMerchant($idMerchant)
        );

        if ($merchantUserResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage('Merchant user was successfully created.');

            return $this->redirectResponse($this->getMerchantUserListUrl($idMerchant));
        }

        foreach ($merchantUserResponseTransfer->getErrors() as $errors) {
            $this->addErrorMessage($errors->getMessage());
        }

        return $this->viewResponse([
            'merchantUserForm' => $merchantUserForm->createView(),
            'idMerchant' => $idMerchant,
        ]);
    }
}
