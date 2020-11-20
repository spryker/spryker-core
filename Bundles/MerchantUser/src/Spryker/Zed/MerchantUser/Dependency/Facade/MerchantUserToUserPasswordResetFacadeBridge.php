<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Dependency\Facade;

class MerchantUserToUserPasswordResetFacadeBridge implements MerchantUserToUserPasswordResetFacadeInterface
{
    /**
     * @var \Spryker\Zed\UserPasswordReset\Business\UserPasswordResetFacadeInterface
     */
    protected $userPasswordResetFacade;

    /**
     * @param \Spryker\Zed\UserPasswordReset\Business\UserPasswordResetFacadeInterface $userPasswordResetFacade
     */
    public function __construct($userPasswordResetFacade)
    {
        $this->userPasswordResetFacade = $userPasswordResetFacade;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset(string $email): bool
    {
        return $this->userPasswordResetFacade->requestPasswordReset($email);
    }
}
