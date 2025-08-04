<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\User;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Client\MultiFactorAuthMerchantPortalToSessionClientInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToUserFacadeInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @uses {@link \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationSuccessHandler::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY}
     *
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY = '_multi_factor_auth_login_user_email';

    /**
     * @param \Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Client\MultiFactorAuthMerchantPortalToSessionClientInterface $sessionClient
     */
    public function __construct(
        protected MultiFactorAuthMerchantPortalToUserFacadeInterface $userFacade,
        protected MultiFactorAuthMerchantPortalToSessionClientInterface $sessionClient
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
