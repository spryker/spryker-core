<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Generated\Shared\Transfer\ResponseMessageTransfer;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CreateCompanyUserController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_SUCCESS_COMPANY_USER_CREATE = 'Company user has been created.';
    protected const MESSAGE_ERROR_COMPANY_USER_CREATE = 'Company user has not been created.';

    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCompanyUserFormDataProvider();
        $companyUserForm = $this->getFactory()
            ->getCompanyUserForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($companyUserForm->isSubmitted() && $companyUserForm->isValid()) {
            return $this->createCompanyUser(
                $companyUserForm,
                $request->query->get(static::PARAM_REDIRECT_URL, static::URL_REDIRECT_COMPANY_USER_PAGE)
            );
        }

        return $this->viewResponse([
            'form' => $companyUserForm->createView(),
            'backButton' => static::URL_REDIRECT_COMPANY_USER_PAGE,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function attachCustomerAction(Request $request)
    {
        $idCompanyUser = $this->castId($request->query->get(CompanyUserGuiConfig::PARAM_ID_CUSTOMER));
        $dataProvider = $this->getFactory()->createCustomerCompanyAttachFormDataProvider();

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
                array_map(
                    function (ResponseMessageTransfer $responseMessageTransfer) {
                        $this->addErrorMessage($responseMessageTransfer->getText());
                    },
                    $companyUserResponseTransfer->getMessages()->getArrayCopy()
                );
            } else {
                $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_CREATE);

                return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserForm
     * @param string $redirectUrl
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createCompanyUser(FormInterface $companyUserForm, string $redirectUrl)
    {
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer */
        $companyUserTransfer = $companyUserForm->getData();
        $companyResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->create($companyUserTransfer);

        if (!$companyResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_CREATE);

            return $this->viewResponse([
                'form' => $companyUserForm->createView(),
            ]);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_CREATE);

        return $this->redirectResponse($redirectUrl);
    }
}
