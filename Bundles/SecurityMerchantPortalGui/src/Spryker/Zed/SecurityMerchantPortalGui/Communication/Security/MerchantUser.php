<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Security;

use Generated\Shared\Transfer\MerchantUserTransfer;

class MerchantUser implements MerchantUserInterface
{
    /**
     * @var \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected $merchantUserTransfer;

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
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param string[] $roles
     */
    public function __construct(MerchantUserTransfer $merchantUserTransfer, array $roles = [])
    {
        $merchantUserTransfer->requireUser();
        $this->merchantUserTransfer = $merchantUserTransfer;

        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $merchantUserTransfer->getUser();
        /** @var string $username */
        $username = $userTransfer->requireUsername()->getUsername();
        $this->username = $username;
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
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getMerchantUserTransfer(): MerchantUserTransfer
    {
        return $this->merchantUserTransfer;
    }
}
