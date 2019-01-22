<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\UserSession;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\User\UserConfig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

class UserSession implements UserSessionInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function hasCurrentUser(): bool
    {
        $user = $this->readUserFromSession();

        return $user !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user)
    {
        $key = $this->createUserKey();

        return $this->session->set($key, clone $user);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function getCurrentUser(): ?UserTransfer
    {
        $user = $this->readUserFromSession();

        if ($user === null) {
            return null;
        }

        return clone $user;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag
     */
    public function getUserSessionMetadata(): MetadataBag
    {
        return $this->session->getMetadataBag();
    }

    /**
     * @return string
     */
    protected function createUserKey(): string
    {
        return sprintf('%s:currentUser', UserConfig::USER_SESSION_KEY);
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function readUserFromSession(): ?UserTransfer
    {
        $key = $this->createUserKey();

        if (!$this->session->has($key)) {
            return null;
        }

        return $this->session->get($key);
    }
}
