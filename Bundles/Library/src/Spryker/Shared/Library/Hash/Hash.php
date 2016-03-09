<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Hash;

class Hash
{

    const SHA256 = 'sha256';
    const SHA512 = 'sha512';
    const MD5 = 'md5';

    /**
     * @var self
     */
    private static $instance;

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $algorithm
     * @param mixed $value
     *
     * @return string
     */
    public static function hashValue($algorithm, $value)
    {
        return hash($algorithm, $value);
    }

}
