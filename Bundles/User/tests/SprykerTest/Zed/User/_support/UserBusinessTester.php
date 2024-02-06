<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User;

use Codeception\Actor;
use Orm\Zed\User\Persistence\SpyUserQuery;

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
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\User\PHPMD)
 */
class UserBusinessTester extends Actor
{
    use _generated\UserBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureUserTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getUserQuery());
    }

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    protected function getUserQuery(): SpyUserQuery
    {
        return SpyUserQuery::create();
    }
}
