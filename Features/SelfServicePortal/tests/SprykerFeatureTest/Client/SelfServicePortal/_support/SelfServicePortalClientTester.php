<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Client\SelfServicePortal;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;

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
 * @method void pause($vars = [])
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerFeatureTest\Client\SelfServicePortal\PHPMD)
 */
class SelfServicePortalClientTester extends Actor
{
    use _generated\SelfServicePortalClientTesterActions;

    public function generateSspModelStorageKey(int $modelId, string $storeName): string
    {
        return sprintf('ssp_model:%s:%d', $storeName, $modelId);
    }

    public function haveCompanyUserWithPermissions(
        CompanyTransfer $companyTransfer,
        PermissionCollectionTransfer $permissionCollectionTransfer
    ): CompanyUserTransfer {
        $companyRoleTransfer = $this->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $businessUnitTransfer = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyRoleCollection = (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer);

        $customerTransfer = $this->haveCustomer();
        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $companyUserTransfer->setCompanyRoleCollection($companyRoleCollection);
        $companyUserTransfer->setCompany($companyTransfer);
        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);
        $companyUserTransfer->setCustomer($customerTransfer);

        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);

        return $companyUserTransfer;
    }
}
