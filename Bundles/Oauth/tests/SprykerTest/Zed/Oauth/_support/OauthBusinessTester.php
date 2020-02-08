<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth;

use Codeception\Actor;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;

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
     * @param string|null $customerReference
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer
     */
    public function createRevokeRefreshTokenRequestTransfer(?string $refreshToken = null, ?string $customerReference = null): RevokeRefreshTokenRequestTransfer
    {
        $revokeRefreshTokenRequestTransfer = new RevokeRefreshTokenRequestTransfer();
        if ($refreshToken) {
            $revokeRefreshTokenRequestTransfer->setRefreshToken($refreshToken);
        }
        $revokeRefreshTokenRequestTransfer->setCustomerReference($customerReference ?? $this->haveCustomer()->getCustomerReference());

        return $revokeRefreshTokenRequestTransfer;
    }
}
