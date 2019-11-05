<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProfileDataImport;

use Codeception\Actor;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddressQuery;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProfileDataImportCommunicationTester extends Actor
{
    use _generated\MerchantProfileDataImportCommunicationTesterActions;

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
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @return void
     */
    public function truncateMerchantProfileAddressRelations(): void
    {
        $this->truncateTableRelations($this->getMerchantProfileAddressQuery());
    }

    /**
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddressQuery
     */
    protected function getMerchantProfileAddressQuery(): SpyMerchantProfileAddressQuery
    {
        return SpyMerchantProfileAddressQuery::create();
    }
}
