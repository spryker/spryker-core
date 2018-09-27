<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Business\Model;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Auth\Persistence\Map\SpyResetPasswordTableMap;
use Orm\Zed\Auth\Persistence\SpyResetPassword;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Dependency\Facade\AuthToUserInterface;
use Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface;
use Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

class PasswordReset
{
    public const LENGTH = 22;

    /**
     * @var \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface
     */
    protected $authQueryContainer;

    /**
     * @var \Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface
     */
    protected $userPasswordResetNotificationSender;

    /**
     * @var \Spryker\Zed\Auth\Dependency\Facade\AuthToUserInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\Auth\AuthConfig
     */
    protected $authConfig;

    /**
     * @param \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface $authQueryContainer
     * @param \Spryker\Zed\Auth\Dependency\Facade\AuthToUserInterface $userFacade
     * @param \Spryker\Zed\Auth\AuthConfig $authConfig
     */
    public function __construct(
        AuthQueryContainerInterface $authQueryContainer,
        AuthToUserInterface $userFacade,
        AuthConfig $authConfig
    ) {
        $this->authQueryContainer = $authQueryContainer;
        $this->userFacade = $userFacade;
        $this->authConfig = $authConfig;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function requestToken($email)
    {
        try {
            $userTransfer = $this->userFacade->getUserByUsername($email);

            $passwordResetToken = $this->generateToken();
            $result = $this->persistResetPassword($passwordResetToken, $userTransfer);
            $this->sendResetRequest($email, $passwordResetToken);

            return $result;
        } catch (UserNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @param string $passwordResetToken
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function persistResetPassword($passwordResetToken, UserTransfer $userTransfer)
    {
        $resetPasswordEntity = new SpyResetPassword();
        $resetPasswordEntity->setCode($passwordResetToken);
        $resetPasswordEntity->setFkUser($userTransfer->getIdUser());
        $resetPasswordEntity->setStatus(SpyResetPasswordTableMap::COL_STATUS_ACTIVE);

        $affectedRows = $resetPasswordEntity->save();

        return $affectedRows > 0;
    }

    /**
     * @param string $token
     * @param string $newPassword
     *
     * @return bool
     */
    public function resetPassword($token, $newPassword)
    {
        $resetPasswordEntity = $this->authQueryContainer->queryForActiveCode($token)->findOne();

        if (empty($resetPasswordEntity)) {
            return false;
        }

        $userTransfer = $this->userFacade->getUserById($resetPasswordEntity->getFkUser());
        $userTransfer->setPassword($newPassword);
        $this->userFacade->updateUser($userTransfer);

        $resetPasswordEntity->setStatus(SpyResetPasswordTableMap::COL_STATUS_USED);
        $affectedRows = $resetPasswordEntity->save();

        return $affectedRows > 0;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidToken($token)
    {
        $resetPasswordEntity = $this->authQueryContainer->queryForActiveCode($token)->findOne();

        if (empty($resetPasswordEntity)) {
            return false;
        }

        $expiresInSeconds = $this->authConfig->getPasswordTokenExpirationInSeconds();
        $expiresAt = $resetPasswordEntity->getCreatedAt();
        $expiresAt->add(new DateInterval('PT' . $expiresInSeconds . 'S'));

        $currentDateTime = new DateTime();

        if ($currentDateTime > $expiresAt) {
            $resetPasswordEntity->setStatus(SpyResetPasswordTableMap::COL_STATUS_EXPIRED);
            $resetPasswordEntity->save();

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function generateToken()
    {
        $utilTextService = new UtilTextService();

        return $utilTextService->generateRandomString(8);
    }

    /**
     * @param string $email
     * @param string $passwordResetToken
     *
     * @return void
     */
    protected function sendResetRequest($email, $passwordResetToken)
    {
        if ($this->userPasswordResetNotificationSender !== null) {
            $this->userPasswordResetNotificationSender->send($email, $passwordResetToken);
        }
    }

    /**
     * @param \Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface $userPasswordResetNotificationSender
     *
     * @return void
     */
    public function setUserPasswordResetNotificationSender(
        AuthPasswordResetSenderInterface $userPasswordResetNotificationSender
    ) {
        $this->userPasswordResetNotificationSender = $userPasswordResetNotificationSender;
    }
}
