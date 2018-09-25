<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CreateCompanyUserController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    /**
     * @see ListCompanyUserController::indexAction()
     */
    protected const URL_USER_LIST = '/company-user-gui/list-company-user';

    protected const MESSAGE_SUCCESS_COMPANY_USER_CREATE = 'Company User "%s" has been created.';
    protected const MESSAGE_ERROR_COMPANY_USER_CREATE = 'Company User "%s" has not been created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL, static::URL_USER_LIST);

        $dataProvider = $this->getFactory()->createCompanyUserFormDataProvider();
        $form = $this->getFactory()
            ->getCompanyUserForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer */
            $companyUserTransfer = $form->getData();
            $companyResponseTransfer = $this->getFactory()
                ->getCompanyUserFacade()
                ->create($companyUserTransfer);

            if (!$companyResponseTransfer->getIsSuccessful()) {
                $this->addErrorMessage(sprintf(
                    static::MESSAGE_ERROR_COMPANY_USER_CREATE,
                    $companyUserTransfer->getCustomer()->getFirstName() . ' ' . $companyUserTransfer->getCustomer()->getLastName()
                ));

                return $this->viewResponse([
                    'form' => $form->createView(),
                ]);
            }

            $this->addSuccessMessage(sprintf(
                static::MESSAGE_SUCCESS_COMPANY_USER_CREATE,
                $companyUserTransfer->getCustomer()->getFirstName() . ' ' . $companyUserTransfer->getCustomer()->getLastName()
            ));

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'backButton' => static::URL_USER_LIST,
        ]);
    }
}
