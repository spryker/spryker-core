<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantDataImport;

use Codeception\Actor;
use Orm\Zed\Merchant\Persistence\SpyMerchantAddressQuery;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;

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
     * @return void
     */
    public function truncateMerchantRelations(): void
    {
        $this->truncateTableRelations($this->getMerchantQuery());
    }

    /**
     * @return void
     */
    public function truncateMerchantAddressRelations(): void
    {
        $this->truncateTableRelations($this->getMerchantAddressQuery());
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantAddressQuery
     */
    protected function getMerchantAddressQuery(): SpyMerchantAddressQuery
    {
        return SpyMerchantAddressQuery::create();
    }
}
