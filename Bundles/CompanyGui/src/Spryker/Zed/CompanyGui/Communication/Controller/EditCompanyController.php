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
    protected const MESSAGE_COMPANY_UPDATE_SUCCESS = 'Company has been updated.';
    protected const MESSAGE_COMPANY_UPDATE_ERROR = 'Company has not been updated.';
    protected const MESSAGE_COMPANY_NOT_FOUND = 'Company not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCompany = $this->castId($request->get(static::URL_PARAM_ID_COMPANY));
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $dataProvider = $this->getFactory()->createCompanyFormDataProvider();
        $formData = $dataProvider->getData($idCompany);

        if (!$formData->getIdCompany()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_NOT_FOUND);

            return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
        }

        $form = $this->getFactory()
            ->getCompanyForm(
                $dataProvider->getData($idCompany),
                $dataProvider->getOptions($idCompany)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyTransfer = $form->getData();
            $companyResponseTransfer = $this->getFactory()
                ->getCompanyFacade()
                ->update($companyTransfer);

            if (!$companyResponseTransfer->getIsSuccessful()) {
                $this->addErrorMessage(static::MESSAGE_COMPANY_UPDATE_ERROR);

                return $this->viewResponse([
                    'form' => $form->createView(),
                    'idCompany' => $idCompany,
                ]);
            }

            $this->addSuccessMessage(static::MESSAGE_COMPANY_UPDATE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCompany' => $idCompany,
        ]);
    }

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
