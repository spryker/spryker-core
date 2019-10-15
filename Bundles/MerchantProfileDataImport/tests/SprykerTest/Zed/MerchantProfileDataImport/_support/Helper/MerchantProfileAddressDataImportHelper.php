<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProfileDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddressQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantProfileAddressDataImportHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function assertMerchantProfileAddressDatabaseTableIsEmpty(): void
    {
        $query = $this->getMerchantProfileAddressQuery();
        $this->assertEquals(0, $query->count(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertMerchantProfileAddressDatabaseTableContainsData(): void
    {
        $query = $this->getMerchantProfileAddressQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddressQuery
     */
    protected function getMerchantProfileAddressQuery(): SpyMerchantProfileAddressQuery
    {
        return SpyMerchantProfileAddressQuery::create();
    }

    /**
     * @param string $key
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress
     */
    public function findMerchantProfileAddressByKey(string $key): SpyMerchantProfileAddress
    {
        return $this->getMerchantProfileAddressQuery()
            ->filterByKey($key)
            ->findOne();
    }
}
