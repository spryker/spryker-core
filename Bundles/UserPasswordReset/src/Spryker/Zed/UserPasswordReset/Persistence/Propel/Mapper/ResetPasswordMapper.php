<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ResetPasswordTransfer;
use Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword;

class ResetPasswordMapper
{
    /**
     * @param \Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword $resetPasswordEntity
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer
     */
    public function mapResetPasswordEntityToResetPasswordTransfer(
        SpyResetPassword $resetPasswordEntity,
        ResetPasswordTransfer $resetPasswordTransfer
    ): ResetPasswordTransfer {
        $resetPasswordTransfer->fromArray($resetPasswordEntity->toArray(), true);

        return $resetPasswordTransfer
            ->setIdResetPassword($resetPasswordEntity->getIdAuthResetPassword())
            ->setFkUserId($resetPasswordEntity->getFkUser());
    }

    /**
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     * @param \Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword $resetPasswordEntity
     *
     * @return \Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword
     */
    public function mapResetPasswordTransferToResetPasswordEntity(
        ResetPasswordTransfer $resetPasswordTransfer,
        SpyResetPassword $resetPasswordEntity
    ): SpyResetPassword {
        $resetPasswordEntity->fromArray($resetPasswordTransfer->toArray());

        /** @var int $fkUser */
        $fkUser = $resetPasswordTransfer->getFkUserId();
        /** @var int $idResetPassword */
        $idResetPassword = $resetPasswordTransfer->getIdResetPassword();
        /** @var string $status */
        $status = $resetPasswordTransfer->getStatus();

        return $resetPasswordEntity
            ->setIdAuthResetPassword($idResetPassword)
            ->setFkUser($fkUser)
            ->setStatus($status);
    }
}
