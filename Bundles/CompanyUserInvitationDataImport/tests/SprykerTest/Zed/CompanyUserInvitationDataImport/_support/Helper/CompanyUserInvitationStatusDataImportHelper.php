<?php


/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserInvitationDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitationStatusQuery;

class CompanyUserInvitationStatusDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $companyUserInvitationStatusQuery = $this->getCompanyUserInvitationStatusQuery();
        $companyUserInvitationStatusQuery->find()->delete();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $companyUserInvitationStatusQuery = $this->getCompanyUserInvitationStatusQuery();
        $this->assertTrue(($companyUserInvitationStatusQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\CompanyUserInvitation\Persistence\SpyCompanyUserInvitationStatusQuery
     */
    protected function getCompanyUserInvitationStatusQuery(): SpyCompanyUserInvitationStatusQuery
    {
        return SpyCompanyUserInvitationStatusQuery::create();
    }
}
