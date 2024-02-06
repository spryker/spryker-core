<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Builder;

use Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Form\AgentMerchantLoginForm;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OptionsBuilder implements OptionsBuilderInterface
{
    /**
     * @see \Symfony\Component\Form\Extension\Csrf\CsrfExtension::loadTypeExtensions()
     *
     * @var string
     */
    protected const FORM_FIELD_CSRF_TOKEN = '_token';

    /**
     * @var \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig
     */
    protected AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $agentMerchantUserProvider;

    /**
     * @var \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface
     */
    protected SymfonyVersionCheckerInterface $symfonyVersionChecker;

    /**
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $agentMerchantUserProvider
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface $symfonyVersionChecker
     */
    public function __construct(
        AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig,
        UserProviderInterface $agentMerchantUserProvider,
        SymfonyVersionCheckerInterface $symfonyVersionChecker
    ) {
        $this->agentSecurityMerchantPortalGuiConfig = $agentSecurityMerchantPortalGuiConfig;
        $this->agentMerchantUserProvider = $agentMerchantUserProvider;
        $this->symfonyVersionChecker = $symfonyVersionChecker;
    }

    /**
     * @return array<string, mixed>
     */
    public function buildOptions(): array
    {
        $options = [
            'context' => $this->agentSecurityMerchantPortalGuiConfig->getSecurityFirewallName(),
            'pattern' => $this->agentSecurityMerchantPortalGuiConfig->getRoutePatternAgentMerchantPortal(),
            'form' => [
                'login_path' => $this->agentSecurityMerchantPortalGuiConfig->getRouteLogin(),
                'check_path' => $this->agentSecurityMerchantPortalGuiConfig->getUrlLoginCheck(),
                'username_parameter' => AgentMerchantLoginForm::FORM_NAME . '[' . AgentMerchantLoginForm::FIELD_USERNAME . ']',
                'password_parameter' => AgentMerchantLoginForm::FORM_NAME . '[' . AgentMerchantLoginForm::FIELD_PASSWORD . ']',
                'csrf_parameter' => AgentMerchantLoginForm::FORM_NAME . '[' . static::FORM_FIELD_CSRF_TOKEN . ']',
                'csrf_token_id' => AgentMerchantLoginForm::FORM_NAME,
                'with_csrf' => true,
                'authenticators' => [
                    $this->agentSecurityMerchantPortalGuiConfig->getSecurityAgentMerchantPortalLoginFormAuthenticatorName(),
                ],
            ],
            'logout' => [
                'logout_path' => $this->agentSecurityMerchantPortalGuiConfig->getUrlLogout(),
                'target_url' => $this->agentSecurityMerchantPortalGuiConfig->getRouteLogin(),
                'priority' => 65,
            ],
            'users' => function () {
                return $this->agentMerchantUserProvider;
            },
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => $this->agentSecurityMerchantPortalGuiConfig->getRolePreviousAdmin(),
            ],
        ];

        if ($this->symfonyVersionChecker->isSymfonyVersion5()) {
            $options['anonymous'] = true;
        }

        return $options;
    }
}
