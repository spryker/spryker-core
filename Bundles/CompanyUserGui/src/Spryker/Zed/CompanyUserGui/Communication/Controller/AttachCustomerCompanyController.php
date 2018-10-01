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
class AttachCustomerCompanyController extends AbstractController
{
    protected const REDIRECT_URL_DEFAULT = '/company-user-gui/list-company-user';
    protected const REDIRECT_URL_CUSTOMER_LIST = '/customer';

    protected const MESSAGE_COMPANY_USER_CREATE_SUCCESS = 'Company user has been created.';
    protected const MESSAGE_COMPANY_USER_CREATE_ERROR = 'Company user has not been created.';
    protected const MESSAGE_COMPANY_USER_ATTACH_ERROR = 'Company user for this customer already exists.';

    protected const PARAM_ID_CUSTOMER = 'id-customer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCompanyUser = (int)$request->query->get(static::PARAM_ID_CUSTOMER);
        $dataProvider = $this->getFactory()->createCustomerCompanyAttachFormDataProvider();

        if(!$idCompanyUser || $dataProvider->getData($idCompanyUser)->getIdCompanyUser()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_USER_ATTACH_ERROR);
            return $this->redirectResponse(static::REDIRECT_URL_CUSTOMER_LIST);
        }

        $form = $this->getFactory()
            ->getCustomerCompanyAttachForm(
                $dataProvider->getData($idCompanyUser),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyUserTransfer = $form->getData();
            $companyUserResponseTransfer = $this->getFactory()
                ->getCompanyUserFacade()
                ->create($companyUserTransfer);

            if (!$companyUserResponseTransfer->getIsSuccessful()) {
                $this->addErrorMessage(static::MESSAGE_COMPANY_USER_CREATE_ERROR);

                return $this->viewResponse([
                    'form' => $form->createView(),
                ]);
            }

            $this->addSuccessMessage(static::MESSAGE_COMPANY_USER_CREATE_SUCCESS);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
