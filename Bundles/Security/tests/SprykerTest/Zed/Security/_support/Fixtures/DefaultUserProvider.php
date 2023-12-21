<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Security\Fixtures;

use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DefaultUserProvider implements UserProviderInterface
{
    /**
     * @var array<mixed>
     */
    protected const USERS = [
        'user' => [
            'password' => '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu',
            'roles' => ['ROLE_USER'],
        ],
    ];

    /**
     * @var string
     */
    protected const PASSWORD = 'password';

    /**
     * @var string
     */
    protected const ROLES = 'roles';

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return new InMemoryUser($userEmail, static::USERS[$userEmail][static::PASSWORD]);
    }

    /**
     * @param string $identifier
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return new InMemoryUser($identifier, static::USERS[$identifier][static::PASSWORD], static::USERS[$identifier][static::ROLES]);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return true;
    }
}
