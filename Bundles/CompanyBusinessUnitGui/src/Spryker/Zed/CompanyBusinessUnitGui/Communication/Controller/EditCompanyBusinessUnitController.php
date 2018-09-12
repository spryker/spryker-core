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

    protected const MESSAGE_BUSINESS_UNIT_NOT_FOUND = 'Company business unit not found';

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
        $formData = $dataProvider->getData($idCompanyBusinessUnit);

        if (!$formData->getIdCompanyBusinessUnit()) {
            $this->addErrorMessage(static::MESSAGE_BUSINESS_UNIT_NOT_FOUND);

            return $this->redirectResponse(static::URL_BUSINESS_UNIT_LIST);
        }

        $form = $this->getFactory()
            ->getCompanyBusinessUnitEditForm(
                $formData,
                $dataProvider->getOptions($idCompanyBusinessUnit)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyBusinessUnitTransfer = $form->getData();
            $companyResponseTransfer = $this->getFactory()
                ->getCompanyBusinessUnitFacade()
                ->update($companyBusinessUnitTransfer);

            if (!$companyResponseTransfer->getIsSuccessful()) {
                foreach ($companyResponseTransfer->getMessages() as $message) {
                    $this->addErrorMessage($message->getText());
                }

                return $this->viewResponse([
                    'form' => $form->createView(),
                    'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
                ]);
            }

            foreach ($companyResponseTransfer->getMessages() as $message) {
                $this->addSuccessMessage($message->getText());
            }

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCompany' => $idCompanyBusinessUnit,
        ]);
    }
}
