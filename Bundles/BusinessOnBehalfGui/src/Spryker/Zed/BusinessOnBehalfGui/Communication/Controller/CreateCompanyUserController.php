<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Controller;

use Spryker\Zed\BusinessOnBehalfGui\BusinessOnBehalfGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
            return $this->attachCustomer($form);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function attachCustomer(FormInterface $companyUserForm)
    {
        $companyUserTransfer = $companyUserForm->getData();
        $existsCompanyUser = $this->getFactory()
            ->getCompanyBusinessUnitFacade()
            ->checkCompanyUserByBusinessUnitIdCompanyUserIdExists($companyUserTransfer);

        if ($existsCompanyUser) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_ALREADY_ATTACHED);

            return $this->viewResponse([
                'form' => $companyUserForm->createView(),
            ]);
        }

        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->create($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_CREATE);
        } else {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_CREATE);

            return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        return $this->viewResponse([
            'form' => $companyUserForm->createView(),
        ]);
    }
}
