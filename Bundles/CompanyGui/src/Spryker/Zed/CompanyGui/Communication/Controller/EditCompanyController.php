<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 */
class EditCompanyController extends AbstractController
{
    public const URL_PARAM_ID_COMPANY = 'id-company';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';
    public const REDIRECT_URL_DEFAULT = '/company-gui/list-company';

    public const MESSAGE_COMPANY_ACTIVATE_SUCCESS = 'Company has been activated.';
    public const MESSAGE_COMPANY_DEACTIVATE_SUCCESS = 'Company has been deactivated.';
    public const MESSAGE_COMPANY_APPROVE_SUCCESS = 'Company has been approved.';
    public const MESSAGE_COMPANY_DENY_SUCCESS = 'Company has been denied.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request)
    {
        $idCompany = $this->castId($request->query->get(static::URL_PARAM_ID_COMPANY));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $companyTransfer = $this->createCompanyTransfer();
        $companyTransfer
            ->setIdCompany($idCompany)
            ->setIsActive(true);

        $this->getFactory()
            ->getCompanyFacade()
            ->update($companyTransfer);

        $this->addSuccessMessage(static::MESSAGE_COMPANY_ACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request)
    {
        $idCompany = $this->castId($request->query->get(static::URL_PARAM_ID_COMPANY));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $companyTransfer = $this->createCompanyTransfer();
        $companyTransfer
            ->setIdCompany($idCompany)
            ->setIsActive(false);

        $this->getFactory()
            ->getCompanyFacade()
            ->update($companyTransfer);

        $this->addSuccessMessage(static::MESSAGE_COMPANY_DEACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approveAction(Request $request)
    {
        $idCompany = $this->castId($request->query->get(static::URL_PARAM_ID_COMPANY));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $companyTransfer = $this->createCompanyTransfer();
        $companyTransfer->setIdCompany($idCompany)->setStatus(SpyCompanyTableMap::COL_STATUS_APPROVED);

        $this->getFactory()
            ->getCompanyFacade()
            ->update($companyTransfer);

        $this->addSuccessMessage(static::MESSAGE_COMPANY_APPROVE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function denyAction(Request $request)
    {
        $idCompany = $this->castId($request->query->get(static::URL_PARAM_ID_COMPANY));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $companyTransfer = $this->createCompanyTransfer();
        $companyTransfer->setIdCompany($idCompany)->setStatus(SpyCompanyTableMap::COL_STATUS_DENIED);

        $this->getFactory()
            ->getCompanyFacade()
            ->update($companyTransfer);

        $this->addSuccessMessage(static::MESSAGE_COMPANY_DENY_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createCompanyTransfer(): CompanyTransfer
    {
        return new CompanyTransfer();
    }
}
