<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 */
class AddCompanyController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const REDIRECT_URL_DEFAULT = '/company-gui/list-company';

    protected const MESSAGE_COMPANY_CREATE_SUCCESS = 'Company has been created.';
    protected const MESSAGE_COMPANY_CREATE_ERROR = 'Company has not been created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $dataProvider = $this->getFactory()->createCompanyFormDataProvider();
        $form = $this->getFactory()
            ->getCompanyForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyTransfer = $form->getData();
            $companyResponseTransfer = $this->getFactory()
                ->getCompanyFacade()
                ->create($companyTransfer);

            if (!$companyResponseTransfer->getIsSuccessful()) {
                $this->addErrorMessage(static::MESSAGE_COMPANY_CREATE_ERROR);

                return $this->viewResponse([
                    'form' => $form->createView(),
                ]);
            }

            $this->addSuccessMessage(static::MESSAGE_COMPANY_CREATE_SUCCESS);

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }
}
