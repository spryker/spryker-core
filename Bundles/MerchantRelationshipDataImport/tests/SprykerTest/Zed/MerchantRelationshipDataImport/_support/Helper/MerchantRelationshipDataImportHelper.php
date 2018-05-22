<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantRelationshipDataImportHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $query = $this->getMerchantRelationshipQuery();
        $query->deleteAll();
    }

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
     * @param string $key
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship
     */
    public function findMerchantRelationshipByKey(string $key)
    {
        return $this->getMerchantRelationshipQuery()
            ->filterByKey($key)
            ->findOne();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade()
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }
}
