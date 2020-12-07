<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Strategy;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Business\Creator\OauthUserCreatorInterface;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;

class CreateUserAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Business\Creator\OauthUserCreatorInterface
     */
    protected $oauthUserCreator;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\SecurityOauthUser\Business\Creator\OauthUserCreatorInterface $oauthUserCreator
     */
    public function __construct(
        SecurityOauthUserToUserFacadeInterface $userFacade,
        OauthUserCreatorInterface $oauthUserCreator
    ) {
        $this->userFacade = $userFacade;
        $this->oauthUserCreator = $oauthUserCreator;
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
            return $this->oauthUserCreator->createOauthUser($userCriteriaTransfer);
        }

        if ($userTransfer->getStatus() !== SecurityOauthUserConfig::OAUTH_USER_STATUS_ACTIVE) {
            return null;
        }

        return $userTransfer;
    }
}
