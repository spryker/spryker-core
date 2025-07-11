<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class PostMerchantUserLoginMultiFactorAuthenticationPlugin extends AbstractPlugin implements PostLoginMultiFactorAuthenticationPluginInterface
{
    /**
     * @var string
     */
    protected const MERCHANT_USER_POST_AUTHENTICATION_TYPE = 'MERCHANT_USER_POST_AUTHENTICATION_TYPE';

    /**
     * @uses {@link \Spryker\Zed\SecurityMerchantPortalGui\Communication\Expander\SecurityBuilderExpander::SECURITY_FIREWALL_NAME}
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'AgentMerchantUser';

    /**
     * @uses {@link \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler\MerchantUserAuthenticationSuccessHandler::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY}
     *
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY = '_multi_factor_auth_login_user_email';

    /**
     * @var string
     */
    protected const SECURITY_SESSION_KEY_PLACEHOLDER = '_security_%s';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $authenticationType
     *
     * @return bool
     */
    public function isApplicable(string $authenticationType): bool
    {
        return $authenticationType === static::MERCHANT_USER_POST_AUTHENTICATION_TYPE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $email
     *
     * @return void
     */
    public function createToken(string $email): void
    {
        $user = $this->getFactory()->createMerchantUserProvider()->loadUserByIdentifier($email);

        $token = new PostAuthenticationToken(
            $user,
            static::SECURITY_FIREWALL_NAME,
            $user->getRoles(),
        );

        $tokenStorage = $this->getFactory()->getTokenStorageService();
        $tokenStorage->setToken($token);

        $this->getFactory()->getSessionClient()->set(sprintf(static::SECURITY_SESSION_KEY_PLACEHOLDER, static::SECURITY_FIREWALL_NAME), serialize($token));
        $this->getFactory()->getSessionClient()->remove(static::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function executeOnAuthenticationSuccess(AbstractTransfer $userTransfer): void
    {
        $merchantUserTransfer = (new MerchantUserTransfer())->setUser($userTransfer);
        $this->getFactory()->createMerchantUserAuthenticationSuccessHandler()->executeOnAuthenticationSuccess($merchantUserTransfer);
    }
}
