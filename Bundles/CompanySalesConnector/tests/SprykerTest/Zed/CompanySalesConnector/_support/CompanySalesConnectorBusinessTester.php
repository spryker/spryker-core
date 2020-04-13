<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 *
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
 * @method \Spryker\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanySalesConnectorBusinessTester extends Actor
{
    use _generated\CompanySalesConnectorBusinessTesterActions;

    /**
     * @param string $companyUuid
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteWithCompanyUser(string $companyUuid): QuoteTransfer
    {
        $companyTransfer = (new CompanyTransfer())->setUuid($companyUuid);
        $companyUserTransfer = (new CompanyUserTransfer())->setCompany($companyTransfer);
        $customerTransfer = (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer);

        return (new QuoteTransfer())->setCustomer($customerTransfer);
    }

    /**
     * @param string $permissionKey
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUserWithPermission(string $permissionKey): CompanyUserTransfer
    {
        $companyTransfer = $this->haveCompany();

        $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission(
            $this->getLocator()->permission()->facade()->findPermissionByKey($permissionKey)
        );

        $companyRoleTransfer = $this->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY_ROLE_COLLECTION => (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer),
        ]);

        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);

        return $companyUserTransfer
            ->setCompany($companyTransfer);
    }
}
