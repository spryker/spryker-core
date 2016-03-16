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
        $length = $this->length / 2;
        $token = bin2hex(random_bytes($length));

        if (strlen($token) !== $this->length) {
            $token = str_pad($token, $this->length, '0');
        }

        return $token;
    }

}
