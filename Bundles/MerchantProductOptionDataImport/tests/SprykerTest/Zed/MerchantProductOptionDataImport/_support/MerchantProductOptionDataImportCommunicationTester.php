<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOptionDataImport;

use Codeception\Actor;
use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery;

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
class MerchantProductOptionDataImportCommunicationTester extends Actor
{
    use _generated\MerchantProductOptionDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantProductOptionGroupTableIsEmpty(): void
    {
        $this->getMerchantProductOptionGroupQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMerchantProductOptionGroupTableContainsData(): void
    {
        $this->assertTrue($this->getMerchantProductOptionGroupQuery()->count() > 0);
    }

    /**
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery
     */
    protected function getMerchantProductOptionGroupQuery(): SpyMerchantProductOptionGroupQuery
    {
        return SpyMerchantProductOptionGroupQuery::create();
    }
}
