<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationshipDataImport;

use Codeception\Actor;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;

/**
 * Inherited Methods
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
class PriceProductMerchantRelationshipDataImportCommunicationTester extends Actor
{
    use _generated\PriceProductMerchantRelationshipDataImportCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return void
     */
    public function truncateMerchantRelationshipRelations(): void
    {
        $this->truncateTableRelations($this->getMerchantRelationshipQuery());
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }
}
