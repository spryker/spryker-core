<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityExtension\Configuration;

use Spryker\Shared\SecurityExtension\Exception\FirewallNotFoundException;
use Spryker\Shared\SecurityExtension\Exception\SecurityConfigurationException;

class SecurityConfiguration implements SecurityBuilderInterface, SecurityConfigurationInterface
{
    /**
     * @var array
     */
    protected $firewalls = [];

    /**
     * @var array
     */
    protected $mergableFirewalls = [];

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
     * @var bool
     */
    protected $isFrozen = false;

    /**
     * @param string $firewallName
     * @param array $configuration
     *
     * @return $this
     */
    public function addFirewall(string $firewallName, array $configuration)
    {
        $this->assertNotFrozen();

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
        $this->assertNotFrozen();

        $this->mergableFirewalls[$firewallName] = $configuration;

        return $this;
    }

    /**
     * @return array
     */
    public function getFirewalls(): array
    {
        $this->assertFrozen();

        return $this->firewalls;
    }

    /**
     * @param array $accessRules
     *
     * @return $this
     */
    public function addAccessRules(array $accessRules)
    {
        $this->assertNotFrozen();

        $this->accessRules = array_merge($this->accessRules, $accessRules);

        return $this;
    }

    /**
     * @return array
     */
    public function getAccessRules(): array
    {
        $this->assertFrozen();

        return $this->accessRules;
    }

    /**
     * @param array $roleHierarchy
     *
     * @return $this
     */
    public function addRoleHierarchy(array $roleHierarchy)
    {
        $this->assertNotFrozen();

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
        $this->assertFrozen();

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
        $this->assertNotFrozen();

        $this->authenticationSuccessHandlers[$firewallName] = $authenticationSuccessHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAuthenticationSuccessHandlers(): array
    {
        $this->assertFrozen();

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
        $this->assertNotFrozen();

        $this->authenticationFailureHandlers[$firewallName] = $authenticationFailureHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAuthenticationFailureHandlers(): array
    {
        $this->assertFrozen();

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
        $this->assertNotFrozen();

        $this->logoutHandlers[$firewallName] = $logoutHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getLogoutHandlers(): array
    {
        $this->assertFrozen();

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
        $this->assertNotFrozen();

        $this->accessDeniedHandlers[$firewallName] = $accessDeniedHandler;

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getAccessDeniedHandlers(): array
    {
        $this->assertFrozen();

        return $this->accessDeniedHandlers;
    }

    /**
     * @param callable $eventSubscriber
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function addEventSubscriber(callable $eventSubscriber)
    {
        $this->assertNotFrozen();

        $this->eventSubscribers[] = $eventSubscriber;

        return $this;
    }

    /**
     * @return array
     */
    public function getEventSubscribers(): array
    {
        $this->assertFrozen();

        return $this->eventSubscribers;
    }

    /**
     * @throws \Spryker\Shared\SecurityExtension\Exception\FirewallNotFoundException
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface
     */
    public function getConfiguration(): SecurityConfigurationInterface
    {
        $this->assertNotFrozen();

        $this->isFrozen = true;

        foreach ($this->mergableFirewalls as $firewallName => $configuration) {
            if (!isset($this->firewalls[$firewallName])) {
                throw new FirewallNotFoundException(sprintf('You tried to merge a firewall "%s" which is not configured.', $firewallName));
            }

            $this->firewalls[$firewallName] = array_merge_recursive($this->firewalls[$firewallName], $configuration);
        }

        return $this;
    }

    /**
     * @throws \Spryker\Shared\SecurityExtension\Exception\SecurityConfigurationException
     *
     * @return void
     */
    protected function assertNotFrozen()
    {
        if ($this->isFrozen) {
            throw new SecurityConfigurationException('The configuration is marked as frozen and can\'t be changed.');
        }
    }

    /**
     * @throws \Spryker\Shared\SecurityExtension\Exception\SecurityConfigurationException
     *
     * @return void
     */
    protected function assertFrozen()
    {
        if (!$this->isFrozen) {
            throw new SecurityConfigurationException('Please use "\Spryker\Shared\SecurityExtension\Configuration\SecurityConfiguration::getConfiguration()" to retrieve the security configuration.');
        }
    }
}
