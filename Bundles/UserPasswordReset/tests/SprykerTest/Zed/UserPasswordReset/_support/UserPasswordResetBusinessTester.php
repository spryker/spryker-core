<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\UserPasswordReset;

use Codeception\Actor;
use Generated\Shared\Transfer\ResetPasswordTransfer;
use Orm\Zed\UserPasswordReset\Persistence\SpyResetPasswordQuery;
use Spryker\Zed\UserPasswordReset\Business\UserPasswordResetFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class UserPasswordResetBusinessTester extends Actor
{
    use _generated\UserPasswordResetBusinessTesterActions;

    /**
     * @return \Spryker\Zed\UserPasswordReset\Business\UserPasswordResetFacadeInterface
     */
    public function getUserPasswordReset(): UserPasswordResetFacadeInterface
    {
        return $this->getLocator()->userPasswordReset()->facade();
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer|null
     */
    public function findResetPasswordTransferByIdUser(int $idUser): ?ResetPasswordTransfer
    {
        $resetPasswordEntity = $this->createResetPasswordPropelQuery()
            ->filterByFkUser($idUser)
            ->findOne();

        if (!$resetPasswordEntity) {
            return null;
        }

        return (new ResetPasswordTransfer())->fromArray($resetPasswordEntity->toArray(), true)
            ->setIdResetPassword($resetPasswordEntity->getIdAuthResetPassword())
            ->setFkUserId($resetPasswordEntity->getFkUser());
    }

    /**
     * @param int $idAuthResetPassword
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     *
     * @return void
     */
    public function updateResetPasswordByIdAuthResetPassword(int $idAuthResetPassword, ResetPasswordTransfer $resetPasswordTransfer): void
    {
        $resetPasswordEntity = $this->createResetPasswordPropelQuery()
            ->filterByIdAuthResetPassword($idAuthResetPassword)
            ->findOne();

        $resetPasswordEntity->fromArray($resetPasswordTransfer->toArray());

        /** @var int $fkUser */
        $fkUser = $resetPasswordTransfer->getFkUserId();
        /** @var int $idResetPassword */
        $idResetPassword = $resetPasswordTransfer->getIdResetPassword();
        /** @var string $status */
        $status = $resetPasswordTransfer->getStatus();

        $resetPasswordEntity
            ->setIdAuthResetPassword($idResetPassword)
            ->setFkUser($fkUser)
            ->setStatus($status)
            ->save();
    }

    /**
     * @return \Orm\Zed\UserPasswordReset\Persistence\SpyResetPasswordQuery
     */
    protected function createResetPasswordPropelQuery(): SpyResetPasswordQuery
    {
        return SpyResetPasswordQuery::create();
    }
}
