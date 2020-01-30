<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
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
     * @param string|null $refreshToken
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer
     */
    public function createRevokeRefreshTokenRequestTransfer(?string $refreshToken = null, ?CustomerTransfer $customerTransfer = null): RevokeRefreshTokenRequestTransfer
    {
        $revokeRefreshTokenRequestTransfer = new RevokeRefreshTokenRequestTransfer();
        if ($refreshToken) {
            $revokeRefreshTokenRequestTransfer->setRefreshToken($refreshToken);
        }
        $revokeRefreshTokenRequestTransfer->setCustomer($customerTransfer ?? $this->haveCustomer());

        return $revokeRefreshTokenRequestTransfer;
    }

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
