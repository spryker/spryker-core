<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Controller;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 */
class CreateCompanyUserController extends AbstractController
{
    protected const MESSAGE_SUCCESS_COMPANY_USER_CREATE = 'Customer has been attached to business unit.';
    protected const MESSAGE_ERROR_COMPANY_USER_CREATE = 'Customer has not been attached to business unit.';

    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';

    protected const PARAM_ID_CUSTOMER = 'id-customer';
    protected const PARAM_ID_COMPANY = 'id-company';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function attachCustomerAction(Request $request)
    {
        $idCustomer = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER));
        $idCompany = $this->castId($request->query->get(static::PARAM_ID_COMPANY));
        $dataProvider = $this->getFactory()->createCustomerCompanyAttachFormDataProvider();
        $companyUserTransfer = $dataProvider->getData($idCustomer, $idCompany);

        $form = $this->getFactory()
            ->getCustomerBusinessUnitAttachForm($companyUserTransfer, $dataProvider->getOptions($companyUserTransfer))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyUserResponseTransfer = $this->getFactory()
                ->getCompanyUserFacade()
                ->create($form->getData());

            if ($companyUserResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_CREATE);

                return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
            }

            $this->handleErrorMessages($companyUserResponseTransfer->getMessages());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ResponseMessageTransfer[] $errorMessageTransfers
     *
     * @return void
     */
    protected function handleErrorMessages(ArrayObject $errorMessageTransfers): void
    {
        if (count($errorMessageTransfers) === 0) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_CREATE);
            return;
        }

        foreach ($errorMessageTransfers as $errorMessageTransfer) {
            $this->addErrorMessage($errorMessageTransfer->getText());
        }
    }
}
