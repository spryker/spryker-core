<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model\Token;

class Token implements TokenInterface
{
    /**
     * @param string $rawToken
     * @param array<string, mixed> $options
     *
     * @return string
     */
    public function generate($rawToken, array $options = [])
    {
        return base64_encode(password_hash($rawToken, PASSWORD_DEFAULT, $options));
    }

    /**
     * @param string $rawToken
     * @param string $hashedToken
     *
     * @return bool
     */
    public function check($rawToken, $hashedToken)
    {
        return password_verify($rawToken, base64_decode($hashedToken));
    }
}
