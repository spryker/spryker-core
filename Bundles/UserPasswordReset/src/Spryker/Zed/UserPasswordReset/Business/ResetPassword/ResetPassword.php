<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Business\ResetPassword;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\ResetPasswordCriteriaTransfer;
use Generated\Shared\Transfer\ResetPasswordTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\UserPasswordReset\Dependency\Facade\UserPasswordResetToUserFacadeInterface;
use Spryker\Zed\UserPasswordReset\Dependency\Service\UserPasswordResetToUtilTextServiceInterface;
use Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetEntityManagerInterface;
use Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetRepositoryInterface;
use Spryker\Zed\UserPasswordReset\UserPasswordResetConfig;

class ResetPassword implements ResetPasswordInterface
{
    protected const RANDOM_STRING_LENGTH = 8;

    protected const STATUS_ACTIVE = 'active';
    protected const STATUS_EXPIRED = 'expired';
    protected const STATUS_USED = 'used';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Controller\PasswordController::PARAM_TOKEN
     */
    protected const PARAM_TOKEN = 'token';

    /**
     * @var \Spryker\Zed\UserPasswordReset\Dependency\Facade\UserPasswordResetToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\UserPasswordReset\Dependency\Service\UserPasswordResetToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetEntityManagerInterface
     */
    protected $userPasswordResetEntityManager;

    /**
     * @var \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetRepositoryInterface
     */
    protected $passwordResetRepository;

    /**
     * @var \Spryker\Zed\UserPasswordReset\UserPasswordResetConfig
     */
    protected $resetConfig;

    /**
     * @var array|\Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin\UserPasswordResetRequestHandlerPluginInterface[]
     */
    protected $userPasswordResetRequestHandlerPlugins;

    /**
     * @param \Spryker\Zed\UserPasswordReset\Dependency\Facade\UserPasswordResetToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\UserPasswordReset\Dependency\Service\UserPasswordResetToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetEntityManagerInterface $userPasswordResetEntityManager
     * @param \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetRepositoryInterface $passwordResetRepository
     * @param \Spryker\Zed\UserPasswordReset\UserPasswordResetConfig $resetConfig
     * @param \Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin\UserPasswordResetRequestHandlerPluginInterface[] $userPasswordResetRequestHandlerPlugins
     */
    public function __construct(
        UserPasswordResetToUserFacadeInterface $userFacade,
        UserPasswordResetToUtilTextServiceInterface $utilTextService,
        UserPasswordResetEntityManagerInterface $userPasswordResetEntityManager,
        UserPasswordResetRepositoryInterface $passwordResetRepository,
        UserPasswordResetConfig $resetConfig,
        array $userPasswordResetRequestHandlerPlugins
    ) {
        $this->userFacade = $userFacade;
        $this->utilTextService = $utilTextService;
        $this->userPasswordResetEntityManager = $userPasswordResetEntityManager;
        $this->passwordResetRepository = $passwordResetRepository;
        $this->resetConfig = $resetConfig;
        $this->userPasswordResetRequestHandlerPlugins = $userPasswordResetRequestHandlerPlugins;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset(string $email): bool
    {
        $userTransfer = $this->userFacade->findUser((new UserCriteriaTransfer())->setEmail($email));

        if (!$userTransfer) {
            return false;
        }

        $token = $this->generateToken();
        $resetPasswordTransfer = $this->userPasswordResetEntityManager->createResetPassword(
            (new ResetPasswordTransfer())
                ->setFkUserId($userTransfer->getIdUser())
                ->setCode($token)
                ->setStatus(static::STATUS_ACTIVE)
        );

        $this->executeUserPasswordResetRequestHandlerPlugins(
            $this->createUserPasswordResetRequestTransfer($userTransfer, $token)
        );

        return (bool)$resetPasswordTransfer->getIdResetPassword();
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken(string $token): bool
    {
        $resetPasswordTransfer = $this->passwordResetRepository->findOne(
            (new ResetPasswordCriteriaTransfer())->setCode($token)
        );

        if (!$resetPasswordTransfer) {
            return false;
        }

        if ($this->isExpiredPasswordResetToken($resetPasswordTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function setNewPassword(string $token, string $password): bool
    {
        $resetPasswordTransfer = $this->passwordResetRepository->findOne(
            (new ResetPasswordCriteriaTransfer())->setCode($token)
        );

        if (!$resetPasswordTransfer) {
            return false;
        }

        /** @var int $idUser */
        $idUser = $resetPasswordTransfer->getFkUserId();
        $userTransfer = $this->userFacade->getUserById($idUser);
        $userTransfer->setPassword($password);
        $this->userFacade->updateUser($userTransfer);

        $resetPasswordTransfer->setStatus(static::STATUS_USED);
        $this->userPasswordResetEntityManager->updateResetPassword($resetPasswordTransfer);

        return true;
    }

    /**
     * @return string
     */
    protected function generateToken(): string
    {
        return $this->utilTextService->generateRandomString(static::RANDOM_STRING_LENGTH);
    }

    /**
     * @param \Generated\Shared\Transfer\ResetPasswordTransfer $resetPasswordTransfer
     *
     * @return bool
     */
    protected function isExpiredPasswordResetToken(ResetPasswordTransfer $resetPasswordTransfer): bool
    {
        /** @var string $createdAt */
        $createdAt = $resetPasswordTransfer->getCreatedAt();
        $expiresAt = new DateTime($createdAt);
        $expiresAt->add(new DateInterval(sprintf('PT%dS', $this->resetConfig->getPasswordTokenExpirationInSeconds())));

        if ($expiresAt >= new DateTime()) {
            return false;
        }

        $resetPasswordTransfer->setStatus(static::STATUS_EXPIRED);
        $this->userPasswordResetEntityManager->updateResetPassword($resetPasswordTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $token
     *
     * @return \Generated\Shared\Transfer\UserPasswordResetRequestTransfer
     */
    protected function createUserPasswordResetRequestTransfer(UserTransfer $userTransfer, string $token): UserPasswordResetRequestTransfer
    {
        return (new UserPasswordResetRequestTransfer())
            ->setUser($userTransfer)
            ->setResetPasswordLink($this->createResetPasswordLink($token));
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function createResetPasswordLink(string $token): string
    {
        $query = $this->generateResetPasswordLinkQuery($token);

        return sprintf('%s%s?%s', $this->resetConfig->getBaseUrlZed(), $this->resetConfig->getPasswordResetPath(), $query);
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function generateResetPasswordLinkQuery(string $token): string
    {
        return http_build_query([
            static::PARAM_TOKEN => $token,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return void
     */
    protected function executeUserPasswordResetRequestHandlerPlugins(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): void
    {
        foreach ($this->userPasswordResetRequestHandlerPlugins as $userPasswordResetRequestHandlerPlugin) {
            $userPasswordResetRequestHandlerPlugin->handleUserPasswordResetRequest($userPasswordResetRequestTransfer);
        }
    }
}
