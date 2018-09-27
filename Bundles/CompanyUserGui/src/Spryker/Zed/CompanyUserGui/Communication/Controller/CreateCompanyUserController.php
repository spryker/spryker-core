<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CreateCompanyUserController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\ListCompanyUserController::indexAction()
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
        $dataProvider = $this->getFactory()->createCompanyUserFormDataProvider();
        $companyUserForm = $this->getFactory()
            ->getCompanyUserForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($companyUserForm->isSubmitted() && $companyUserForm->isValid()) {
            return $this->crateCompanyUser(
                $companyUserForm,
                $request->query->get(static::PARAM_REDIRECT_URL, static::URL_USER_LIST)
            );
        }

        return $this->viewResponse([
            'form' => $companyUserForm->createView(),
            'backButton' => static::URL_USER_LIST,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserForm
     * @param string $redirectUrl
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function crateCompanyUser(FormInterface $companyUserForm, string $redirectUrl)
    {
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer */
        $companyUserTransfer = $companyUserForm->getData();
        $companyResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->create($companyUserTransfer);

        $customerFullName = $companyUserTransfer->getCustomer()->getFirstName() . ' ' . $companyUserTransfer->getCustomer()->getLastName();

        if (!$companyResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(sprintf(static::MESSAGE_ERROR_COMPANY_USER_CREATE, $customerFullName));

            return $this->viewResponse([
                'form' => $companyUserForm->createView(),
            ]);
        }

        $this->addSuccessMessage(sprintf(static::MESSAGE_SUCCESS_COMPANY_USER_CREATE, $customerFullName));

        return $this->redirectResponse($redirectUrl);
    }
}
