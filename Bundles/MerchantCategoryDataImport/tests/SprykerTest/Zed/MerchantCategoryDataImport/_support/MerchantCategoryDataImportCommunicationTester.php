<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantCategoryDataImport;

use Codeception\Actor;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;

/**
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
class MerchantCategoryDataImportCommunicationTester extends Actor
{
    use _generated\MerchantCategoryDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantCategoryTablesIsEmpty(): void
    {
        $this->createMerchantCategoryPropelQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery
     */
    protected function createMerchantCategoryPropelQuery(): SpyMerchantCategoryQuery
    {
        return SpyMerchantCategoryQuery::create();
    }
}
