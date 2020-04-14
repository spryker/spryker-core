<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabelDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;

class CompanyUnitAddressLabelRelationDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureRelationTableIsEmpty(): void
    {
        $companyUnitAddressLabelToCompanyUnitAddressQuery = new SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery();
        $companyUnitAddressLabelToCompanyUnitAddressQuery->deleteAll();
    }

    /**
     * @return void
     */
    public function assertRelationTableContainsData(): void
    {
        $companyUnitAddressLabelToCompanyUnitAddressQuery = new SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery();
        $this->assertTrue(($companyUnitAddressLabelToCompanyUnitAddressQuery->count() > 0), 'Expected at least one entry in the relation table but relation table is empty');
    }
}
