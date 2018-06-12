<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class EditCompanyBusinessUnitController extends AbstractController
{
    /**
     * @see CompanyBusinessUnitForm::FIELD_ID_COMPANY_BUSINESS_UNIT
     */
    protected const PARAM_ID_COMPANY_BUSINESS_UNIT = 'id-company-business-unit';
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    /**
     * @see ListCompanyBusinessUnitController::indexAction()
     */
    protected const URL_BUSINESS_UNIT_LIST = '/company-business-unit-gui/list-company-business-unit';

    protected const MESSAGE_COMPANY_BUSINESS_UNIT_UPDATE_SUCCESS = 'Company Business Unit has been updated.';
    protected const MESSAGE_COMPANY_BUSINESS_UNIT_UPDATE_ERROR = 'Company Business Unit has not been updated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCompanyBusinessUnit = $this->castId($request->query->get(static::PARAM_ID_COMPANY_BUSINESS_UNIT));
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL, static::URL_BUSINESS_UNIT_LIST);

        $dataProvider = $this->getFactory()->createCompanyBusinessUnitFormDataProvider();
        $form = $this->getFactory()
            ->getCompanyBusinessUnitEditForm(
                $dataProvider->getData($idCompanyBusinessUnit),
                $dataProvider->getOptions($idCompanyBusinessUnit)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyBusinessUnitTransfer = $form->getData();
            $companyResponseTransfer = $this->getFactory()
                ->getCompanyBusinessUnitFacade()
                ->update($companyBusinessUnitTransfer);

            if (!$companyResponseTransfer->getIsSuccessful()) {
                $this->addErrorMessage(static::MESSAGE_COMPANY_BUSINESS_UNIT_UPDATE_ERROR);

                return $this->viewResponse([
                    'form' => $form->createView(),
                    'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
                ]);
            }

            $this->addSuccessMessage(static::MESSAGE_COMPANY_BUSINESS_UNIT_UPDATE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCompany' => $idCompanyBusinessUnit,
        ]);
    }
}
