<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth;

use Codeception\Actor;
use Generated\Shared\DataBuilder\RevokeRefreshTokenRequestBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
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
class OauthBusinessTester extends Actor
{
    use _generated\OauthBusinessTesterActions;

    protected const TEST_PASSWORD = 'Test password';

    /**
     * @param string $customerReference
     * @param string|null $refreshToken
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer
     */
    public function createRevokeRefreshTokenRequestTransfer(string $customerReference, ?string $refreshToken = null): RevokeRefreshTokenRequestTransfer
    {
        $revokeRefreshTokenRequestTransfer = (new RevokeRefreshTokenRequestBuilder())
            ->seed([RevokeRefreshTokenRequestTransfer::REFRESH_TOKEN => $refreshToken, RevokeRefreshTokenRequestTransfer::CUSTOMER_REFERENCE => $customerReference])
            ->build();

        return $revokeRefreshTokenRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerTransfer(): CustomerTransfer
    {
        return $this->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
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
