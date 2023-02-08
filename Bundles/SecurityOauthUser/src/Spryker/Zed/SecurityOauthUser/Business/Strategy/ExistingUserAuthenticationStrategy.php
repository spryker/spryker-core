<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Strategy;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;

class ExistingUserAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig
     */
    protected $securityOauthUserConfig;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig $securityOauthUserConfig
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface $userFacade
     */
    public function __construct(
        SecurityOauthUserConfig $securityOauthUserConfig,
        SecurityOauthUserToUserFacadeInterface $userFacade
    ) {
        $this->securityOauthUserConfig = $securityOauthUserConfig;
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
        $userCriteriaTransfer->requireEmail();
        $userConditionsTransfer = (new UserConditionsTransfer())->addUsername($userCriteriaTransfer->getEmailOrFail());
        $userCriteriaTransfer->setUserConditions($userConditionsTransfer);

        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);
        if ($userCollectionTransfer->getUsers()->count() === 0) {
            return null;
        }

        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        if ($userTransfer->getStatus() !== $this->securityOauthUserConfig->getOauthUserStatusActive()) {
            return null;
        }

        return $userTransfer;
    }
}
