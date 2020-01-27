<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth;

use Codeception\Actor;
use Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery;

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
class OauthBusinessTester extends Actor
{
    use _generated\OauthBusinessTesterActions;

    /**
     * @return int
     */
    public function getOauthRefreshTokensCount(): int
    {
        return SpyOauthRefreshTokenQuery::create()->count();
    }

    /**
     * @return int
     */
    public function deleteAllOauthRefreshTokens(): int
    {
        return SpyOauthRefreshTokenQuery::create()->deleteAll();
    }
}
