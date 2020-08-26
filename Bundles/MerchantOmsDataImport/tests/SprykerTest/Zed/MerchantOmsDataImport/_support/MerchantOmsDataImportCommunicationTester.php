<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantOmsDataImport;

use Codeception\Actor;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantOmsDataImportCommunicationTester extends Actor
{
    use _generated\MerchantOmsDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function assertStateMachineProcessDatabaseTableContainsData(): void
    {
        $this->assertTrue(
            $this->getStateMachineProcessPropelQuery()->exists(),
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @param string $key
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant|null
     */
    public function findMerchantByKey(string $key): ?SpyMerchant
    {
        return $this->getMerchantPropelQuery()->filterByMerchantKey($key)->findOne();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantPropelQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    protected function getStateMachineProcessPropelQuery(): SpyStateMachineProcessQuery
    {
        return SpyStateMachineProcessQuery::create();
    }
}
