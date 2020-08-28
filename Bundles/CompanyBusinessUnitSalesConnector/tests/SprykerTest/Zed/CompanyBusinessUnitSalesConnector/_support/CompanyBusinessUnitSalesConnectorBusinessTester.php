<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyBusinessUnitSalesConnectorBusinessTester extends Actor
{
    use _generated\CompanyBusinessUnitSalesConnectorBusinessTesterActions;

    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUserWithPermission(string $permissionKey): CompanyUserTransfer
    {
        $companyTransfer = $this->haveCompany();

        $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission(
            $this->getLocator()->permission()->facade()->findPermissionByKey($permissionKey)
        );

        $companyRoleTransfer = $this->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $companyRoleCollectionTransfer = (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer);

        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::COMPANY_ROLE_COLLECTION => $companyRoleCollectionTransfer,
        ]);

        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);

        return $companyUserTransfer
            ->setCompanyBusinessUnit($companyBusinessUnitTransfer)
            ->setFkCompany($companyTransfer->getIdCompany());
    }

    /**
     * @param string $companyBusinessUnitUuid
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteWithCompanyUser(string $companyBusinessUnitUuid): QuoteTransfer
    {
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->setUuid($companyBusinessUnitUuid);
        $companyUserTransfer = (new CompanyUserTransfer())->setCompanyBusinessUnit($companyBusinessUnitTransfer);
        $customerTransfer = (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer);

        return (new QuoteTransfer())->setCustomer($customerTransfer);
    }
}
