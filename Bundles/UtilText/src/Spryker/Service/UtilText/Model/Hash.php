<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model;

class Hash implements HashInterface
{
    public const SHA256 = 'sha256';
    public const SHA512 = 'sha512';
    public const MD5 = 'md5';

    /**
     * @param mixed $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue($value, $algorithm)
    {
        return hash($algorithm, $value);
    }
}
