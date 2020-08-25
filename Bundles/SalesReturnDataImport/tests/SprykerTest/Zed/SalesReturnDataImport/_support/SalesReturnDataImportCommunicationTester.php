<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\SalesReturnDataImport;

use Codeception\Actor;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery;

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
 * @method void sales-return($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnDataImportCommunicationTester extends Actor
{
    use _generated\SalesReturnDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureReturnReasonTablesIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesReturnReasonQuery());
    }

    /**
     * @module SalesReturn
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery
     */
    protected function getSalesReturnReasonQuery(): SpySalesReturnReasonQuery
    {
        return SpySalesReturnReasonQuery::create();
    }
}
