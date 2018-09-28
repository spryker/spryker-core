<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantRelationshipDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantRelationshipDataImportHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $query = $this->getMerchantRelationshipQuery();
        $this->assertEquals(0, $query->count(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getMerchantRelationshipQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }
}
