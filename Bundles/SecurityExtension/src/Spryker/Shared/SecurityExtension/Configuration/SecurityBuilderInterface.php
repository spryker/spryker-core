<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityExtension\Configuration;

interface SecurityBuilderInterface
{
    /**
     * @param string $firewallName
     * @param array $configuration
     *
     * @return $this
     */
    public function addFirewall(string $firewallName, array $configuration);

    /**
     * @param string $firewallName
     * @param array $configuration
     *
     * @return $this
     */
    public function mergeFirewall(string $firewallName, array $configuration);

    /**
     * @param array $accessRules
     *
     * @return $this
     */
    public function addAccessRules(array $accessRules);

    /**
     * @param array $roleHierarchy
     *
     * @return $this
     */
    public function addRoleHierarchy(array $roleHierarchy);

    /**
     * @param string $firewallName
     * @param callable $authenticationSuccessHandler
     *
     * @return $this
     */
    public function addAuthenticationSuccessHandler(string $firewallName, callable $authenticationSuccessHandler);

    /**
     * @param string $firewallName
     * @param callable $authenticationFailureHandler
     *
     * @return $this
     */
    public function addAuthenticationFailureHandler(string $firewallName, callable $authenticationFailureHandler);

    /**
     * @param string $firewallName
     * @param callable $logoutHandler
     *
     * @return $this
     */
    public function addLogoutHandler(string $firewallName, callable $logoutHandler);

    /**
     * @param string $firewallName
     * @param callable $accessDeniedHandler
     *
     * @return $this
     */
    public function addAccessDeniedHandler(string $firewallName, callable $accessDeniedHandler);

    /**
     * @param callable $eventSubscriber
     *
     * @return $this
     */
    public function addEventSubscriber(callable $eventSubscriber);
}
