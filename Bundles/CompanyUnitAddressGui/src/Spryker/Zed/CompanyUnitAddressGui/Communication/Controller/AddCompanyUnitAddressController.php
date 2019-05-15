<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUnitAddressGui\Communication\CompanyUnitAddressGuiCommunicationFactory getFactory()
 */
class AddCompanyUnitAddressController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const REDIRECT_URL_DEFAULT = '/company-unit-address-gui/list-company-unit-address';

    protected const MESSAGE_COMPANY_UNIT_ADDRESS_CREATE_SUCCESS = 'Company unit address has been successfully created.';
    protected const MESSAGE_COMPANY_UNIT_ADDRESS_CREATE_ERROR = 'Company unit address create failed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $redirectUrl = $request->get(static::PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);
        $companyUnitAddressForm = $this->getFactory()
            ->createCompanyUnitAddressForm()
            ->handleRequest($request);

        if ($companyUnitAddressForm->isSubmitted()) {
            $this->createCompanyUnitAddress($companyUnitAddressForm);

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $companyUnitAddressForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUnitAddressForm
     *
     * @return void
     */
    protected function createCompanyUnitAddress(FormInterface $companyUnitAddressForm)
    {
        if (!$companyUnitAddressForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_CREATE_ERROR);

            return;
        }

        $response = $this->getFactory()
            ->getCompanyUnitAddressFacade()
            ->create($companyUnitAddressForm->getData());

        if (!$response->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_CREATE_ERROR);

            return;
        }

        $this->addSuccessMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_CREATE_SUCCESS);
    }
}
