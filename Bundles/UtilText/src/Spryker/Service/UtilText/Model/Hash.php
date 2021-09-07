<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model;

class Hash implements HashInterface
{
    /**
     * @var string
     */
    public const SHA256 = 'sha256';
    /**
     * @var string
     */
    public const SHA512 = 'sha512';
    /**
     * @var string
     */
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
