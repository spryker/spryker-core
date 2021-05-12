<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Security;

use Generated\Shared\Transfer\UserTransfer;

class SecurityOauthUser implements SecurityOauthUserInterface
{
    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $userTransfer;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $password;

    /**
     * @var string[]
     */
    protected $roles = [];

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string[] $roles
     */
    public function __construct(UserTransfer $userTransfer, array $roles = [])
    {
        $this->userTransfer = $userTransfer;
        $this->username = $userTransfer->getUsernameOrFail();
        $this->password = $userTransfer->getPassword();
        $this->roles = $roles;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserTransfer(): UserTransfer
    {
        return $this->userTransfer;
    }
}
