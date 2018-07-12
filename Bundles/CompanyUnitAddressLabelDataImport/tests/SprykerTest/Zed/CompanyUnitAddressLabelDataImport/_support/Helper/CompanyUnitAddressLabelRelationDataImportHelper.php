<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabelDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;

class CompanyUnitAddressLabelRelationDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureRelationTableIsEmpty()
    {
        $companyUnitAddressLabelToCompanyUnitAddressQuery = new SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery();
        $companyUnitAddressLabelToCompanyUnitAddressQuery->deleteAll();
    }

    /**
     * @return void
     */
    public function assertRelationTableContainsData()
    {
        $companyUnitAddressLabelToCompanyUnitAddressQuery = new SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery();
        $this->assertTrue(($companyUnitAddressLabelToCompanyUnitAddressQuery->count() > 0), 'Expected at least one entry in the relation table but relation table is empty');
    }
}
