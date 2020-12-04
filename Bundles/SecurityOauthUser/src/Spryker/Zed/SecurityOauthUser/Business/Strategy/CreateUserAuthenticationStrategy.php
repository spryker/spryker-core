<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Resolver;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;

class CreateUserAuthenticationStrategy implements AuthenticationStrategyInterface
{
    protected const OAUTH_USER_CREATION_DEFAULT_PASSWORD_LENGTH = 64;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig
     */
    protected $securityOauthUserConfig;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig $securityOauthUserConfig
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Service\SecurityOauthUserToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        SecurityOauthUserConfig $securityOauthUserConfig,
        SecurityOauthUserToUserFacadeInterface $userFacade,
        SecurityOauthUserToUtilTextServiceInterface $utilTextService
    ) {
        $this->securityOauthUserConfig = $securityOauthUserConfig;
        $this->userFacade = $userFacade;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @return string
     */
    public function getAuthenticationStrategy(): string
    {
        return SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function resolveOauthUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer
    {
        $userTransfer = $this->userFacade->findUser($userCriteriaTransfer);

        if ($userTransfer === null) {
            return $this->userFacade->createUser(
                $this->createUserTransfer($userCriteriaTransfer)
            );
        }

        if ($userTransfer->getStatus() !== SecurityOauthUserConfig::OAUTH_USER_STATUS_ACTIVE) {
            return null;
        }

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUserTransfer(UserCriteriaTransfer $userCriteriaTransfer): UserTransfer
    {
        $email = $userCriteriaTransfer->getEmailOrFail();

        return (new UserTransfer())
            ->setUsername($email)
            ->setFirstName($email)
            ->setLastName($email)
            ->setPassword($this->utilTextService->generateRandomByteString(
                static::OAUTH_USER_CREATION_DEFAULT_PASSWORD_LENGTH
            ))
            ->setStatus($this->securityOauthUserConfig->getOauthUserCreationStatus());
    }
}
