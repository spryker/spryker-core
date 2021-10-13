<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business\Adapter;

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordEncoderAdapter implements PasswordEncoderAdapterInterface
{
    /**
     * @var int
     */
    protected const BCRYPT_FACTOR = 12;

    /**
     * @param string $encoded
     * @param string $raw
     * @param string|null $salt
     *
     * @return bool
     */
    public function isPasswordValid(string $encoded, string $raw, ?string $salt = null): bool
    {
        return $this
            ->getPasswordEncoder()
            ->isPasswordValid($encoded, $raw, $salt);
    }

    /**
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    protected function getPasswordEncoder(): PasswordEncoderInterface
    {
        if (class_exists(NativePasswordEncoder::class)) {
            return new NativePasswordEncoder();
        }

        return new BCryptPasswordEncoder(static::BCRYPT_FACTOR);
    }
}
