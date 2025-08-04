<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Reader\User;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MultiFactorAuth\Dependency\Client\MultiFactorAuthToSessionClientInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @uses {@link \Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler\UserAuthenticationSuccessHandler::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY}
     *
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY = '_multi_factor_auth_login_user_email';

    /**
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Client\MultiFactorAuthToSessionClientInterface $sessionClient
     */
    public function __construct(
        protected MultiFactorAuthToUserFacadeInterface $userFacade,
        protected MultiFactorAuthToSessionClientInterface $sessionClient
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUser(): UserTransfer
    {
        if ($this->userFacade->hasCurrentUser() === true) {
            return $this->userFacade->getCurrentUser();
        }

        $username = $this->sessionClient->get(static::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY);

        if ($username === null) {
            return new UserTransfer();
        }

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions((new UserConditionsTransfer())
                ->addUsername($username));

        return $this->userFacade->getUserCollection($userCriteriaTransfer)->getUsers()->offsetGet(0);
    }
}
