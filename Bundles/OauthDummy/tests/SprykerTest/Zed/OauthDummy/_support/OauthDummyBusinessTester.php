<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthDummy;

use Codeception\Actor;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\OauthDummy\Business\OauthDummyFacadeInterface getFacade()
 */
class OauthDummyBusinessTester extends Actor
{
    use _generated\OauthDummyBusinessTesterActions;

    /**
     * @param string $token
     *
     * @return array
     */
    public function decodeAccessToken(string $token): array
    {
        return json_decode(
            base64_decode(
                str_replace(
                    '_',
                    '/',
                    str_replace('-', '+', explode('.', $token)[1]),
                ),
            ),
            true,
        );
    }
}
