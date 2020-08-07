<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantDataImport;

use Codeception\Actor;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\Merchant\Persistence\SpyMerchantStoreQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantDataImportCommunicationTester extends Actor
{
    use _generated\MerchantDataImportCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param string[] $references
     *
     * @return void
     */
    public function deleteMerchantByReferences(array $references): void
    {
        $this->getMerchantQuery()->filterByMerchantReference_In($references)->delete();
    }

    /**
     * @return void
     */
    public function truncateMerchantRelations(): void
    {
        $this->truncateTableRelations($this->getMerchantQuery(), ['\\' . SpyUrlQuery::class]);
    }

    /**
     * @return void
     */
    public function ensureMerchantStoreTableIsEmpty(): void
    {
        $merchantStoreQuery = $this->getMerchantStoreQuery();
        $merchantStoreQuery->deleteAll();
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function deleteMerchantByKey(string $key): void
    {
        $merchantQuery = $this->getMerchantQuery()->filterByMerchantKey($key);
        $merchantQuery->delete();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantStoreQuery
     */
    protected function getMerchantStoreQuery(): SpyMerchantStoreQuery
    {
        return SpyMerchantStoreQuery::create();
    }
}
