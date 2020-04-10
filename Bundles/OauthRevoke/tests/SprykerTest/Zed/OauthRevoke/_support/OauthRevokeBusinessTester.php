<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthRevoke;

use Codeception\Actor;
use DateTime;
use Orm\Zed\Oauth\Persistence\SpyOauthClient;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshToken;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshTokenQuery;

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
class OauthRevokeBusinessTester extends Actor
{
    use _generated\OauthRevokeBusinessTesterActions;

    /**
     * @return int
     */
    public function deleteAllOauthRefreshTokens(): int
    {
        return SpyOauthRefreshTokenQuery::create()->deleteAll();
    }

    /**
     * @param string $identifier
     *
     * @return \Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshToken
     */
    public function persistOauthRefreshToken(string $identifier): SpyOauthRefreshToken
    {
        $oauthClient = new SpyOauthClient();
        $oauthClient
            ->setName('clientName')
            ->setIdentifier($identifier)
            ->save();

        $expectedOauthRefreshToken = new SpyOauthRefreshToken();
        $expectedOauthRefreshToken
            ->setIdentifier($identifier)
            ->setUserIdentifier('user identifier')
            ->setFkOauthClient($oauthClient->getIdentifier())
            ->setCustomerReference('customer reference')
            ->setExpiresAt((new DateTime())->format('Y-m-d'))
            ->save();

        return $expectedOauthRefreshToken;
    }
}
