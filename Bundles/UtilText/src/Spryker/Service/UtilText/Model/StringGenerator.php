<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model;

class StringGenerator implements StringGeneratorInterface
{
    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length = 32)
    {
        $tokenLength = (int)($length / 2);
        $token = bin2hex(random_bytes($tokenLength));

        if (strlen($token) !== $length) {
            $token = str_pad($token, $length, '0');
        }

        return $token;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomByteString(int $length = 32): string
    {
        return random_bytes($length);
    }
}
