<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Business;

use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UserPasswordReset\Business\UserPasswordResetBusinessFactory getFactory()
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetRepositoryInterface getRepository()
 */
class UserPasswordResetFacade extends AbstractFacade implements UserPasswordResetFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return bool
     */
    public function requestPasswordReset(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): bool
    {
        return $this->getFactory()->createResetPassword()->requestPasswordReset($userPasswordResetRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken(string $token): bool
    {
        return $this->getFactory()->createResetPassword()->isValidPasswordResetToken($token);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function setNewPassword(string $token, string $password): bool
    {
        return $this->getFactory()->createResetPassword()->setNewPassword($token, $password);
    }
}
