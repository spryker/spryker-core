<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Auth\Token;

class TokenService implements TokenServiceInterface
{

    /**
     * @param string $rawToken
     *
     * @return string
     */
    public function generate($rawToken)
    {
        return base64_encode(password_hash($rawToken, PASSWORD_DEFAULT));
    }

    /**
     * @param string $rawToken
     * @param string $hash
     *
     * @return bool
     */
    public function check($rawToken, $hash)
    {
        return password_verify($rawToken, base64_decode($hash));
    }

}
