<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Generator;

class StringGenerator
{

    /**
     * @var int
     */
    private $length = 32;

    /**
     * @param int $length
     *
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function generateRandomString()
    {
        $function = 'openssl_random_pseudo_bytes';
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            $function = 'random_bytes';
        }

        $length = $this->length / 2;
        $token = bin2hex(call_user_func($function, (int)$length));

        if (strlen($token) !== $this->length) {
            $token = str_pad($token, $this->length, '0');
        }

        return $token;
    }

}
