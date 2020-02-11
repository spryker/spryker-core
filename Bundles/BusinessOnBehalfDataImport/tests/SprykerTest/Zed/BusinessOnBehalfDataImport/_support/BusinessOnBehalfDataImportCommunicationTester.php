<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\BusinessOnBehalfDataImport;

use Codeception\Actor;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;

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
class BusinessOnBehalfDataImportCommunicationTester extends Actor
{
    use _generated\BusinessOnBehalfDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureCompanyUserDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getCompanyUserQuery());
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
