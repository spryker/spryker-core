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
    protected const URL_PARAM_ID_COMPANY_BUSINESS_UNIT = 'id-company-business-unit';
    protected const URL_PARAM_REDIRECT_URL = 'redirect-url';
    protected const REDIRECT_URL_DEFAULT = '/company-business-unit-gui/list-company-business-unit';

    protected const MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_SUCCESS = 'Company Business Unit %d has been deleted.';
    protected const MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_ERROR = 'Company Business Unit %d has not been deleted.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idCompanyBusinessUnit = $this->castId($request->query->get(static::URL_PARAM_ID_COMPANY_BUSINESS_UNIT));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        $companyBusinessUnitFacade = $this
            ->getFactory()
            ->getCompanyBusinessUnitFacade();

        $companyBusinessUnit = $companyBusinessUnitFacade->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
        if ($companyBusinessUnit) {
            $companyBusinessUnitFacade = $this
                ->getFactory()
                ->getCompanyBusinessUnitFacade();

            $companyBusinessUnit = $companyBusinessUnitFacade
                ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

            $companyBusinessUnitFacade->delete($companyBusinessUnit);

            $this->addSuccessMessage(sprintf(
                static::MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_SUCCESS,
                $idCompanyBusinessUnit
            ));
        } else {
            $this->addErrorMessage(sprintf(
                static::MESSAGE_COMPANY_BUSINESS_UNIT_DELETE_ERROR,
                $idCompanyBusinessUnit
            ));
        }

        return $this->redirectResponse(
            Url::generate($redirectUrl)->build()
        );
    }
}
