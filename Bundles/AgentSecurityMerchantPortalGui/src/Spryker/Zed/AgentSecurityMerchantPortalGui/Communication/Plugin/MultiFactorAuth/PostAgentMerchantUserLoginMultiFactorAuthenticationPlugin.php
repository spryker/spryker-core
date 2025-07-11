<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class PostAgentMerchantUserLoginMultiFactorAuthenticationPlugin extends AbstractPlugin implements PostLoginMultiFactorAuthenticationPluginInterface
{
    /**
     * @var string
     */
    protected const AGENT_MERCHANT_USER_POST_AUTHENTICATION_TYPE = 'AGENT_MERCHANT_USER_POST_AUTHENTICATION_TYPE';

    /**
     * @uses {@link \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler\AuthenticationSuccessHandler::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY}
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
        return $authenticationType === static::AGENT_MERCHANT_USER_POST_AUTHENTICATION_TYPE;
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
        $user = $this->getFactory()->createAgentMerchantUserProvider()->loadUserByIdentifier($email);

        $token = new PostAuthenticationToken(
            $user,
            $this->getConfig()->getSecurityFirewallName(),
            $user->getRoles(),
        );
        $tokenStorage = $this->getFactory()->getTokenStorageService();
        $tokenStorage->setToken($token);

        $this->getFactory()->getSessionClient()->set(sprintf(static::SECURITY_SESSION_KEY_PLACEHOLDER, $this->getConfig()->getSecurityFirewallName()), serialize($token));
        $this->getFactory()->getSessionClient()->remove(static::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $userTransfer
     *
     * @return void
     */
    public function executeOnAuthenticationSuccess(AbstractTransfer $userTransfer): void
    {
        $this->getFactory()->createAuthenticationSuccessHandler()->executeOnAuthenticationSuccess($userTransfer);
    }
}
