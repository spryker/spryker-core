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
    protected MerchantUserTransfer $merchantUserTransfer;

    /**
     * @var string
     */
    protected string $username;

    /**
     * @var string|null
     */
    protected ?string $password;

    /**
     * @var array<string>
     */
    protected array $roles = [];

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param array<string> $roles
     */
    public function __construct(MerchantUserTransfer $merchantUserTransfer, array $roles = [])
    {
        $this->merchantUserTransfer = $merchantUserTransfer;

        $userTransfer = $merchantUserTransfer->getUserOrFail();
        $this->username = $userTransfer->getUsernameOrFail();
        $this->password = $userTransfer->getPassword();
        $this->roles = $roles;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return void
     */
    public function eraseCredentials(): void
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
