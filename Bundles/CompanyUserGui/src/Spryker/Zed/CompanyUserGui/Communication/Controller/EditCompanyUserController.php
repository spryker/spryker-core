<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class EditCompanyUserController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS_COMPANY_USER_UPDATE = 'Company User has been updated.';

    /**
     * @var string
     */
    protected const MESSAGE_COMPANY_USER_NOT_FOUND = 'Company User not found.';

    /**
     * @var string
     */
    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCompanyUser = $this->castId($request->query->get(CompanyUserGuiConfig::PARAM_ID_COMPANY_USER));

        $dataProvider = $this->getFactory()->createCompanyUserFormDataProvider();
        $companyUserTransfer = $dataProvider->getData($idCompanyUser);

        if (!$companyUserTransfer->getIdCompanyUser()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_USER_NOT_FOUND);

            return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $companyUserForm = $this->getFactory()
            ->getCompanyUserEditForm(
                $companyUserTransfer,
                $dataProvider->getOptions(),
            )
            ->handleRequest($request);

        if ($companyUserForm->isSubmitted() && $companyUserForm->isValid()) {
            return $this->updateCompanyUser(
                $companyUserForm,
                (string)$request->query->get(static::PARAM_REDIRECT_URL, static::URL_REDIRECT_COMPANY_USER_PAGE),
            );
        }

        return $this->viewResponse([
            'form' => $companyUserForm->createView(),
            'idCompany' => $idCompanyUser,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserForm
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function updateCompanyUser(FormInterface $companyUserForm, string $redirectUrl)
    {
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer */
        $companyUserTransfer = $companyUserForm->getData();
        $companyResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->update($companyUserTransfer);

        if (!$companyResponseTransfer->getIsSuccessful()) {
            foreach ($companyResponseTransfer->getMessages() as $message) {
                $this->addErrorMessage($message->getText());
            }

            return $this->viewResponse([
                'form' => $companyUserForm->createView(),
                'idCompanyUser' => $companyUserTransfer->getIdCompanyUser(),
            ]);
        }

        foreach ($companyResponseTransfer->getMessages() as $message) {
            $this->addSuccessMessage($message->getText());
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_UPDATE);

        return $this->redirectResponse($redirectUrl);
    }
}
