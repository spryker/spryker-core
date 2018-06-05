<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class DeleteCompanyBusinessUnitController extends AbstractController
{
    /**
     * @see CompanyBusinessUnitForm::FIELD_ID_COMPANY_BUSINESS_UNIT
     */
    protected const URL_PARAM_ID_COMPANY_BUSINESS_UNIT = 'id-company-business-unit';
    /**
     * @see ListCompanyBusinessUnitController::indexAction()
     */
    protected const URL_BUSINESS_UNIT_LIST = '/company-business-unit-gui/list-company-business-unit';

    protected const MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_SUCCESS = 'Company Business Unit "%d" was deleted.';
    protected const MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_ERROR = 'You can not delete a business unit "%d" while it contains users';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $redirectUrl = Url::generate(static::URL_BUSINESS_UNIT_LIST)->build();

        $idCompanyBusinessUnit = $this->castId(
            $request->query->get(static::URL_PARAM_ID_COMPANY_BUSINESS_UNIT)
        );

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        $companyBusinessUnitResponseTransfer = $this
            ->getFactory()
            ->getCompanyBusinessUnitFacade()
            ->delete($companyBusinessUnitTransfer);

        if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(sprintf(
                static::MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_SUCCESS,
                $idCompanyBusinessUnit
            ));

            return $this->redirectResponse($redirectUrl);
        }

        $this->addErrorMessage(sprintf(
            static::MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_ERROR,
            $idCompanyBusinessUnit
        ));

        return $this->redirectResponse($redirectUrl);
    }
}
