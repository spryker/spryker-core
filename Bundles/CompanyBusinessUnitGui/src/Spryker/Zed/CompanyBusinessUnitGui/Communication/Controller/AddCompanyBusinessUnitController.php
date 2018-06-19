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
class AddCompanyBusinessUnitController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    /**
     * @see ListCompanyBusinessUnitController::indexAction()
     */
    protected const URL_BUSINESS_UNIT_LIST = '/company-business-unit-gui/list-company-business-unit';

    protected const MESSAGE_COMPANY_BUSINESS_UNIT_CREATE_SUCCESS = 'Company Business Unit has been created.';
    protected const MESSAGE_COMPANY_BUSINESS_UNIT_CREATE_ERROR = 'Company Business Unit has not been created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL, static::URL_BUSINESS_UNIT_LIST);

        $dataProvider = $this->getFactory()->createCompanyBusinessUnitFormDataProvider();
        $form = $this->getFactory()
            ->getCompanyBusinessUnitForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyBusinessUnitTransfer = $form->getData();
            $companyResponseTransfer = $this->getFactory()
                ->getCompanyBusinessUnitFacade()
                ->create($companyBusinessUnitTransfer);

            if (!$companyResponseTransfer->getIsSuccessful()) {
                $this->addErrorMessage(static::MESSAGE_COMPANY_BUSINESS_UNIT_CREATE_ERROR);

                return $this->viewResponse([
                    'form' => $form->createView(),
                ]);
            }

            $this->addSuccessMessage(static::MESSAGE_COMPANY_BUSINESS_UNIT_CREATE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'backButton' => static::URL_BUSINESS_UNIT_LIST,
        ]);
    }
}
