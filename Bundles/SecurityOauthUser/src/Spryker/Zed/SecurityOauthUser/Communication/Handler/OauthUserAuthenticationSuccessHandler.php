<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Handler;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class OauthUserAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    use TargetPathTrait;

    /**
     * @uses \Spryker\Zed\SecurityOauthUser\Communication\Plugin\Security\OauthUserSecurityPlugin::SECURITY_FIREWALL_NAME
     */
    protected const SECURITY_FIREWALL_NAME = 'OauthUser';

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig
     */
    protected $securityOauthUserConfig;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\Dependency\Facade\SecurityOauthUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig $securityOauthUserConfig
     */
    public function __construct(
        SecurityOauthUserToUserFacadeInterface $userFacade,
        SecurityOauthUserConfig $securityOauthUserConfig
    ) {
        $this->userFacade = $userFacade;
        $this->securityOauthUserConfig = $securityOauthUserConfig;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        /** @var \Spryker\Zed\SecurityOauthUser\Communication\Security\SecurityOauthUserInterface $user */
        $user = $token->getUser();

        $this->userFacade->setCurrentUser(
            (new UserTransfer())->setUsername($user->getUsername())
        );

        return $this->createRedirectResponse($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse(Request $request): RedirectResponse
    {
        $targetUrl = $this->getTargetPath($request->getSession(), static::SECURITY_FIREWALL_NAME);

        if ($targetUrl) {
            return new RedirectResponse($targetUrl);
        }

        return new RedirectResponse($this->securityOauthUserConfig->getUrlHome());
    }
}
