<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Dependency\Facade;

class SecurityGuiToUserPasswordResetFacadeBridge implements SecurityGuiToUserPasswordResetFacadeInterface
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

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken(string $token): bool
    {
        return $this->userPasswordResetFacade->isValidPasswordResetToken($token);
    }

    /**
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function setNewPassword(string $token, string $password): bool
    {
        return $this->userPasswordResetFacade->setNewPassword($token, $password);
    }
}
