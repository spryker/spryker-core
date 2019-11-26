<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityExtension\Configuration;

class SecurityConfiguration implements SecurityBuilderInterface, SecurityConfigurationInterface
{
    /**
     * @var array
     */
    protected $firewalls = [];

    /**
     * @var array
     */
    protected $accessRules = [];

    /**
     * @var array
     */
    protected $roleHierarchies = [];

    /**
     * @var callable[]
     */
    protected $authenticationSuccessHandlers = [];

    /**
     * @var callable[]
     */
    protected $authenticationFailureHandlers = [];

    /**
     * @var callable[]
     */
    protected $logoutHandlers = [];

    /**
     * @var callable[]
     */
    protected $accessDeniedHandlers = [];

    /**
     * @var array
     */
    protected $eventSubscribers = [];

    /**
     * @param string $firewallName
     * @param array $configuration
     *
     * @return $this
     */
    public function addFirewall(string $firewallName, array $configuration)
    {
        $this->firewalls[$firewallName] = $configuration;

        return $this;
    }

    /**
     * @param string $firewallName
     * @param array $configuration
     *
     * @return $this
     */
    public function mergeFirewall(string $firewallName, array $configuration)
    {
        $configuration = array_merge_recursive($this->firewalls[$firewallName], $configuration);

        $this->firewalls[$firewallName] = $configuration;

        return $this;
    }

    /**
     * @return array
     */
    public function getFirewalls(): array
    {
        return $this->firewalls;
    }

    /**
     * @param array $accessRules
     *
     * @return $this
     */
    public function addAccessRules(array $accessRules)
    {
        $this->accessRules = array_merge($this->accessRules, $accessRules);

        return $this;
    }

    /**
     * @return array
     */
    public function getAccessRules(): array
    {
        return $this->accessRules;
    }

    /**
     * @param array $roleHierarchy
     *
     * @return $this
     */
    public function addRoleHierarchy(array $roleHierarchy)
    {
        foreach ($roleHierarchy as $mainRole => $hierarchy) {
            $this->roleHierarchies[$mainRole] = $hierarchy;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRoleHierarchies(): array
    {
        return $this->roleHierarchies;
    }

    /**
     * @param string $firewallName
     * @param callable $authenticationSuccessHandler
     *
     * @return $this
     */
    public function addAuthenticationSuccessHandler(string $firewallName, callable $authenticationSuccessHandler)
    {
        $this->authenticationSuccessHandlers[$firewallName] = $authenticationSuccessHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAuthenticationSuccessHandlers(): array
    {
        return $this->authenticationSuccessHandlers;
    }

    /**
     * @param string $firewallName
     * @param callable $authenticationFailureHandler
     *
     * @return $this
     */
    public function addAuthenticationFailureHandler(string $firewallName, callable $authenticationFailureHandler)
    {
        $this->authenticationFailureHandlers[$firewallName] = $authenticationFailureHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAuthenticationFailureHandlers(): array
    {
        return $this->authenticationFailureHandlers;
    }

    /**
     * @param string $firewallName
     * @param callable $logoutHandler
     *
     * @return $this
     */
    public function addLogoutHandler(string $firewallName, callable $logoutHandler)
    {
        $this->logoutHandlers[$firewallName] = $logoutHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getLogoutHandlers(): array
    {
        return $this->logoutHandlers;
    }

    /**
     * @param string $firewallName
     * @param callable $accessDeniedHandler
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function addAccessDeniedHandler(string $firewallName, callable $accessDeniedHandler)
    {
        $this->accessDeniedHandlers[$firewallName] = $accessDeniedHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAccessDeniedHandlers(): array
    {
        return $this->accessDeniedHandlers;
    }

    /**
     * @param callable $eventSubscriber
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function addEventSubscriber(callable $eventSubscriber)
    {
        $this->eventSubscribers[] = $eventSubscriber;

        return $this;
    }

    /**
     * @return array
     */
    public function getEventSubscribers(): array
    {
        return $this->eventSubscribers;
    }

    /**
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface
     */
    public function getConfiguration(): SecurityConfigurationInterface
    {
        return $this;
    }
}
