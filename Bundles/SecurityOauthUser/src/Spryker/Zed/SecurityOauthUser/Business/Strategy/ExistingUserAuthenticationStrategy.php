<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Strategy;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;

class ExistingUserAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface $userFacade
     */
    public function __construct(SecurityOauthUserToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @return string
     */
    public function getAuthenticationStrategy(): string
    {
        return SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_ACCEPT_ONLY_EXISTING_USERS;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function resolveOauthUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer
    {
        $userCriteriaTransfer->getEmailOrFail();

        $userTransfer = $this->userFacade->findUser($userCriteriaTransfer);
        if ($userTransfer === null) {
            return null;
        }

        if ($userTransfer->getStatus() !== SecurityOauthUserConfig::OAUTH_USER_STATUS_ACTIVE) {
            return null;
        }

        return $userTransfer;
    }
}
