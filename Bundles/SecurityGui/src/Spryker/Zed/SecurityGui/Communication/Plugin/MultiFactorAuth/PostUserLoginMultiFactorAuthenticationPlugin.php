<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Plugin\MultiFactorAuth;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @method \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface getFacade()
 */
class PostUserLoginMultiFactorAuthenticationPlugin extends AbstractPlugin implements PostLoginMultiFactorAuthenticationPluginInterface
{
    /**
     * @var string
     */
    protected const USER_POST_AUTHENTICATION_TYPE = 'USER_POST_AUTHENTICATION_TYPE';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Plugin\Security\UserSecurityPlugin::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'User';

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
        return $authenticationType === static::USER_POST_AUTHENTICATION_TYPE;
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
        $user = $this->getFactory()->createUserProvider()->loadUserByIdentifier($email);

        $token = new PostAuthenticationToken(
            $user,
            static::SECURITY_FIREWALL_NAME,
            $user->getRoles(),
        );
        $tokenStorage = $this->getFactory()->getTokenStorage();
        $tokenStorage->setToken($token);
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
        $this->getFactory()->createUserAuthenticationSuccessHandler()->executeOnAuthenticationSuccess($userTransfer);
    }
}
