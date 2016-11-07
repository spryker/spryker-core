<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\UtilText;

class StringGenerator implements StringGeneratorInterface
{

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length = 32)
    {
        $tokenLength = $length / 2;
        $token = bin2hex(random_bytes($tokenLength));

        if (strlen($token) !== $length) {
            $token = str_pad($token, $length, '0');
        }

        return $token;
    }

}
