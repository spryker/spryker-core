<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SspFileManagement;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class SspFileManagementBusinessTester extends Actor
{
    use _generated\SspFileManagementBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer|null $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUser(?CompanyTransfer $companyTransfer = null): CompanyUserTransfer
    {
        if ($companyTransfer === null) {
            $companyTransfer = $this->haveCompany();
        }

        $customerTransfer = $this->haveCustomer();

        return $this->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
    }

    /**
     * @param int $idFile
     *
     * @return int
     */
    public function countFileAttachmentsByIdFile(int $idFile): int
    {
        $companyFileQuery = $this->createCompanyFileQuery();
        $companyFileQuery->filterByFkFile($idFile);

        $companyUserFileQuery = $this->createCompanyUserFileQuery();
        $companyUserFileQuery->filterByFkFile($idFile);

        $companyBusinessUnitFileQuery = $this->createCompanyBusinessUnitFileQuery();
        $companyBusinessUnitFileQuery->filterByFkFile($idFile);

        $queryList = [$companyFileQuery, $companyUserFileQuery, $companyBusinessUnitFileQuery];

        return array_reduce($queryList, fn ($carry, $query) => $carry + $query->count(), 0);
    }
}
