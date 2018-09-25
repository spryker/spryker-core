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
class EditCompanyUserController extends AbstractController
{
    /**
     * @see CompanyUserForm::FIELD_ID_COMPANY_USER
     */
    protected const PARAM_ID_COMPANY_USER = 'id-company-user';
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    /**
     * @see ListCompanyUserController::indexAction()
     */
    protected const URL_USER_LIST = '/company-user-gui/list-company-user';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCompanyUser = $this->castId($request->query->get(static::PARAM_ID_COMPANY_USER));
        $redirectUrl = $request->query->get(static::PARAM_REDIRECT_URL, static::URL_USER_LIST);

        $dataProvider = $this->getFactory()->createCompanyUserFormDataProvider();
        $form = $this->getFactory()
            ->getCompanyUserEditForm(
                $dataProvider->getData($idCompanyUser),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyUserTransfer = $form->getData();
            $companyResponseTransfer = $this->getFactory()
                ->getCompanyUserFacade()
                ->update($companyUserTransfer);

            if (!$companyResponseTransfer->getIsSuccessful()) {
                foreach ($companyResponseTransfer->getMessages() as $message) {
                    $this->addErrorMessage($message->getText());
                }

                return $this->viewResponse([
                    'form' => $form->createView(),
                    'idCompanyUser' => $idCompanyUser,
                ]);
            }

            foreach ($companyResponseTransfer->getMessages() as $message) {
                $this->addSuccessMessage($message->getText());
            }

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCompany' => $idCompanyUser,
        ]);
    }
}
