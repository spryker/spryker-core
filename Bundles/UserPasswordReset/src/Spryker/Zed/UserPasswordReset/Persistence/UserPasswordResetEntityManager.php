<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Persistence;

use Generated\Shared\Transfer\ResetPasswordTransfer;
use Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetPersistenceFactory getFactory()
 */
class UserPasswordResetEntityManager extends AbstractEntityManager implements UserPasswordResetEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer
     */
    public function createResetPassword(ResetPasswordTransfer $resetPasswordTransfer): ResetPasswordTransfer
    {
        return $this->saveResetPassword($resetPasswordTransfer, new SpyResetPassword());
    }

    /**
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer
     */
    public function updateResetPassword(ResetPasswordTransfer $resetPasswordTransfer): ResetPasswordTransfer
    {
        /** @var \Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword $resetPasswordEntity */
        $resetPasswordEntity = $this->getFactory()
            ->createPropelResetPasswordQuery()
            ->filterByIdAuthResetPassword($resetPasswordTransfer->getIdResetPassword())
            ->findOne();

        return $this->saveResetPassword($resetPasswordTransfer, $resetPasswordEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     * @param \Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword $resetPasswordEntity
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer
     */
    protected function saveResetPassword(ResetPasswordTransfer $resetPasswordTransfer, SpyResetPassword $resetPasswordEntity): ResetPasswordTransfer
    {
        $resetPasswordEntity = $this->getFactory()
            ->createPropelResetPasswordMapper()
            ->mapResetPasswordTransferToResetPasswordEntity($resetPasswordTransfer, $resetPasswordEntity);

        $resetPasswordEntity->save();

        return $resetPasswordTransfer->setIdResetPassword($resetPasswordEntity->getIdAuthResetPassword());
    }
}
