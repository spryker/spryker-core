<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Hasher;

use RuntimeException;

class BcryptHasher implements HasherInterface
{
    /**
     * @throw \RuntimeException
     *
     * @param string $string
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function encrypt(string $string): string
    {
        $hash = password_hash($string, PASSWORD_BCRYPT);

        if (!$hash) {
            throw new RuntimeException('Hashing failure.');
        }

        return $hash;
    }

    /**
     * @param string $string
     * @param string $hash
     *
     * @return bool
     */
    public function validate(string $string, string $hash): bool
    {
        return password_verify($string, $hash);
    }
}
