<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\Security\SecurityConfig getConfig()
 */
class SecurityDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_SECURITY = 'PLUGINS_SECURITY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container->set(static::PLUGINS_SECURITY, function () {
            return $this->getSecurityPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface[]
     */
    protected function getSecurityPlugins(): array
    {
        return [];
    }
}
