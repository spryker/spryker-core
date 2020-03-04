<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class CreateMerchantUserController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createMerchantUserCreateFormDataProvider();
        $merchantUserForm = $this->getFactory()
            ->getMerchantUserCreateForm(
                $dataProvider->getData($request->get('merchant-id'), $request->get('user-merchant-id'))
            )
            ->handleRequest($request);

        if ($merchantUserForm->isSubmitted() && $merchantUserForm->isValid()) {
            return $this->createMerchantUser($request, $merchantUserForm);
        }

        return $this->viewResponse([
            'merchantUserForm' => $merchantUserForm->createView(),
            'merchantId' => $request->get('merchant-id'),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createMerchantUser(Request $request, FormInterface $merchantForm)
    {
        $redirectUrl = sprintf(
            '/merchant-gui/edit-merchant?id-merchant=%s%s',
            $request->get('merchant-id'),
            '#tab-content-merchant-user'
        );

        $userTransfer = (new UserTransfer())->fromArray($merchantForm->getData(), true);
        $merchantUserTransfer = (new MerchantUserTransfer())->setUser($userTransfer)
            ->setIdMerchant($request->get('merchant-id'));

        $merchantUserResponseTransfer = $this->getFactory()->getMerchantUserFacade()->create($merchantUserTransfer);

        if ($merchantUserResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage('Merchant user was successfully created');

            return $this->redirectResponse($redirectUrl);
        }

        foreach ($merchantUserResponseTransfer->getErrors() as $errors) {
            $this->addErrorMessage($errors->getMessage());
        }

        return $this->viewResponse([
            'merchantUserForm' => $merchantForm->createView(),
            'merchantId' => $request->get('merchant-id'),
        ]);
    }
}
