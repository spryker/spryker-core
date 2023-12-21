<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business\Adapter;

use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
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
        if ($this->isSymfonyVersion5() === true) {
            return $this->getPasswordEncoder()->isPasswordValid($encoded, $raw, $salt);
        }

        return $this->createPasswordHasher()->verify($encoded, $raw);
    }

    /**
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    protected function getPasswordEncoder(): PasswordEncoderInterface
    {
        return new NativePasswordEncoder();
    }

    /**
     * @return \Symfony\Component\PasswordHasher\PasswordHasherInterface
     */
    public function createPasswordHasher(): PasswordHasherInterface
    {
        return new NativePasswordHasher();
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
