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
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 */
class DeleteCompanyBusinessUnitController extends AbstractController
{
    /**
     * @see CompanyBusinessUnitForm::FIELD_ID_COMPANY_BUSINESS_UNIT
     *
     * @var string
     */
    protected const PARAM_ID_COMPANY_BUSINESS_UNIT = 'id-company-business-unit';

    /**
     * @see ListCompanyBusinessUnitController::indexAction()
     *
     * @var string
     */
    protected const URL_BUSINESS_UNIT_LIST = '/company-business-unit-gui/list-company-business-unit';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS_COMPANY_BUSINESS_UNIT_DELETE = 'Company Business Unit "%s" was deleted.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_COMPANY_BUSINESS_UNIT_DELETE = 'You can not delete a business unit "%s" while it contains users';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $redirectUrl = Url::generate(static::URL_BUSINESS_UNIT_LIST)->build();

        $deleteForm = $this->getFactory()->createDeleteCompanyBusinessUnitForm()->handleRequest($request);

        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->addErrorMessage('CSRF token is not valid');

            return $this->redirectResponse($redirectUrl);
        }

        $idCompanyBusinessUnit = $this->castId(
            $request->query->get(static::PARAM_ID_COMPANY_BUSINESS_UNIT),
        );

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        $companyBusinessUnitResponseTransfer = $this
            ->getFactory()
            ->getCompanyBusinessUnitFacade()
            ->delete($companyBusinessUnitTransfer);

        if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_BUSINESS_UNIT_DELETE, [
                '%s' => $companyBusinessUnitResponseTransfer
                    ->getCompanyBusinessUnitTransfer()
                    ->getName(),
            ]);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_BUSINESS_UNIT_DELETE, [
            '%s' => $companyBusinessUnitResponseTransfer
                ->getCompanyBusinessUnitTransfer()
                ->getName(),
        ]);

        return $this->redirectResponse($redirectUrl);
    }
}
