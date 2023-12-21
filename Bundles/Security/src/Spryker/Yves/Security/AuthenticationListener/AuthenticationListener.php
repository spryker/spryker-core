<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\AuthenticationListener;

class AuthenticationListener implements AuthenticationListenerInterface
{
    /**
     * @var array<string>
     */
    protected const DEFAULT_AUTHENTICATION_LISTENER_FACTORY_TYPES = [
        'logout',
        'pre_auth',
        'form',
        'http',
        'customer_session_validator',
    ];

    /**
     * @var array<string>
     */
    protected static array $authenticationListenerFactoryTypes = [];

    /**
     * @var array<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityAuthenticationListenerFactoryTypeExpanderPluginInterface>
     */
    protected array $securityAuthenticationListenerFactoryTypeExpanderPlugins;

    /**
     * @param array<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityAuthenticationListenerFactoryTypeExpanderPluginInterface> $securityAuthenticationListenerFactoryTypeExpanderPlugins
     */
    public function __construct(array $securityAuthenticationListenerFactoryTypeExpanderPlugins)
    {
        $this->securityAuthenticationListenerFactoryTypeExpanderPlugins = $securityAuthenticationListenerFactoryTypeExpanderPlugins;
    }

    /**
     * @return array<string>
     */
    public function getAuthenticationListenerFactoryTypes(): array
    {
        if (static::$authenticationListenerFactoryTypes === []) {
            $this->initializeAuthenticationListenerFactoryTypes();
        }

        return static::$authenticationListenerFactoryTypes;
    }

    /**
     * @return void
     */
    protected function initializeAuthenticationListenerFactoryTypes(): void
    {
        static::$authenticationListenerFactoryTypes = static::DEFAULT_AUTHENTICATION_LISTENER_FACTORY_TYPES;

        foreach ($this->securityAuthenticationListenerFactoryTypeExpanderPlugins as $securityAuthenticationListenerFactoryTypeExpanderPlugin) {
            static::$authenticationListenerFactoryTypes = $securityAuthenticationListenerFactoryTypeExpanderPlugin->expand(
                static::$authenticationListenerFactoryTypes,
            );
        }
    }
}
