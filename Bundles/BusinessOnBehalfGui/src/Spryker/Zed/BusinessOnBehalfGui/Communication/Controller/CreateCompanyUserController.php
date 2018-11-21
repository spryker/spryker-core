<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 */
class CreateCompanyUserController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS_COMPANY_USER_CREATE = 'Company user has been attached to business unit.';
    protected const MESSAGE_ERROR_COMPANY_USER_CREATE = 'Company user has not been attached to business unit.';
    protected const MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED = 'Company user already attached to this business unit.';

    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function attachCustomerAction(Request $request)
    {
        $idCompanyUser = $this->castId($request->query->get(BusinessOnBehalfGuiConfig::PARAM_ID_COMPANY_USER));
        $dataProvider = $this->getFactory()->createCustomerCompanyAttachFormDataProvider();
        $companyUserTransfer = $dataProvider->getData($idCompanyUser);

        $form = $this->getFactory()
            ->getCustomerBusinessUnitAttachForm($companyUserTransfer, $dataProvider->getOptions($companyUserTransfer))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyUserResponseTransfer = $this->attachCustomer($form->getData());

            if ($companyUserResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function attachCustomer(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->create($companyUserTransfer);

        if ($companyUserResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_CREATE);

            return $companyUserResponseTransfer;
        }

        $this->handleErrorMessages($companyUserResponseTransfer->getMessages());

        return $companyUserResponseTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ResponseMessageTransfer[] $errorMessageTransfers
     *
     * @return void
     */
    protected function handleErrorMessages(ArrayObject $errorMessageTransfers): void
    {
        foreach ($errorMessageTransfers as $errorMessageTransfer) {
            $this->addErrorMessage($errorMessageTransfer->getText());
        }

        $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_CREATE);
    }
}
