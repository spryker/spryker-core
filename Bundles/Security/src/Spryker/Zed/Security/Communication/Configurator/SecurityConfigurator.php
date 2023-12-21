<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Configurator;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface;

class SecurityConfigurator implements SecurityConfiguratorInterface
{
    /**
     * @var \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface|null
     */
    protected static ?SecurityConfigurationInterface $securityConfiguration = null;

    /**
     * @var \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected SecurityBuilderInterface $sharedSecurityConfiguration;

    /**
     * @var array<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface>
     */
    protected array $securityPlugins;

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $sharedSecurityConfiguration
     * @param array<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface> $securityPlugins
     */
    public function __construct(
        SecurityBuilderInterface $sharedSecurityConfiguration,
        array $securityPlugins
    ) {
        $this->sharedSecurityConfiguration = $sharedSecurityConfiguration;
        $this->securityPlugins = $securityPlugins;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface
     */
    public function getSecurityConfiguration(ContainerInterface $container): SecurityConfigurationInterface
    {
        if (static::$securityConfiguration === null) {
            static::$securityConfiguration = $this->getSecurityConfigurationFromPlugins($container);
        }

        return static::$securityConfiguration;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface
     */
    protected function getSecurityConfigurationFromPlugins(ContainerInterface $container): SecurityConfigurationInterface
    {
        $sharedSecurityConfiguration = $this->sharedSecurityConfiguration;

        foreach ($this->securityPlugins as $securityPlugin) {
            $sharedSecurityConfiguration = $securityPlugin->extend($sharedSecurityConfiguration, $container);
        }

        return $sharedSecurityConfiguration->getConfiguration();
    }
}
