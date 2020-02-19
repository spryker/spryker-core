<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantOmsDataImport;

use Codeception\Actor;
use Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery;

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
class MerchantOmsDataImportCommunicationTester extends Actor
{
    use _generated\MerchantOmsDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantOmsProcessTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantOmsProcessPropelQuery());
    }

    /**
     * @return \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery
     */
    protected function getMerchantOmsProcessPropelQuery(): SpyMerchantOmsProcessQuery
    {
        return SpyMerchantOmsProcessQuery::create();
    }
}
