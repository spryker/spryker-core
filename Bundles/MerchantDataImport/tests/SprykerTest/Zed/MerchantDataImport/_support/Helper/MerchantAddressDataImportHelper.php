<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Merchant\Persistence\SpyMerchantAddress;
use Orm\Zed\Merchant\Persistence\SpyMerchantAddressQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantAddressDataImportHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function assertMerchantAddressDatabaseTableIsEmpty(): void
    {
        $query = $this->getMerchantAddressQuery();
        $this->assertEquals(0, $query->count(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertMerchantAddressDatabaseTableContainsData(): void
    {
        $query = $this->getMerchantAddressQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantAddressQuery
     */
    protected function getMerchantAddressQuery(): SpyMerchantAddressQuery
    {
        return SpyMerchantAddressQuery::create();
    }

    /**
     * @param string $key
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantAddress
     */
    public function findMerchantAddressByKey(string $key): SpyMerchantAddress
    {
        return $this->getMerchantAddressQuery()
            ->filterByKey($key)
            ->findOne();
    }
}
