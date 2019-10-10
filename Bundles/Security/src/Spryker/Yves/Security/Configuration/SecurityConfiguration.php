<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Configuration;

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
    protected $authenticationSuccessHandler = [];

    /**
     * @var callable[]
     */
    protected $authenticationFailureHandler = [];

    /**
     * @var callable[]
     */
    protected $accessDeniedHandler = [];

    /**
     * @var array
     */
    protected $eventSubscriber = [];

    /**
     * @param string $firewallName
     * @param array $configuration
     *
     * @return $this
     */
    public function addFirewall(string $firewallName, array $configuration)
    {
        if (isset($this->firewalls[$firewallName])) {
            $configuration = array_merge_recursive($this->firewalls[$firewallName], $configuration);
        }

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
        $this->accessRules[] = $accessRules;

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
        $this->authenticationSuccessHandler[$firewallName] = $authenticationSuccessHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAuthenticationSuccessHandler(): array
    {
        return $this->authenticationSuccessHandler;
    }

    /**
     * @param string $firewallName
     * @param callable $authenticationFailureHandler
     *
     * @return $this
     */
    public function addAuthenticationFailureHandler(string $firewallName, callable $authenticationFailureHandler)
    {
        $this->authenticationFailureHandler[$firewallName] = $authenticationFailureHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAuthenticationFailureHandler(): array
    {
        return $this->authenticationFailureHandler;
    }

    /**
     * @param string $firewallName
     * @param callable $accessDeniedHandler
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    public function addAccessDeniedHandler(string $firewallName, callable $accessDeniedHandler)
    {
        $this->accessDeniedHandler[$firewallName] = $accessDeniedHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAccessDeniedHandler(): array
    {
        return $this->accessDeniedHandler;
    }

    /**
     * @param callable $eventSubscriber
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    public function addEventSubscriber(callable $eventSubscriber)
    {
        $this->eventSubscriber[] = $eventSubscriber;

        return $this;
    }

    /**
     * @return array
     */
    public function getEventSubscriber(): array
    {
        return $this->eventSubscriber;
    }
}
